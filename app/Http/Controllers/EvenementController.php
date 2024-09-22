<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Note;
use App\Models\Salle;
use App\Jobs\NotifJob;
use App\Models\Filiere;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Evenement;
use App\Models\Universite;
use App\Jobs\NotifJobModif;
use App\Models\Commentaire;
use App\Models\Institution;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Jobs\UpdateColorsJob;
use App\Models\AnneeAcademique;
use App\Models\UniteEnseignement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;


class EvenementController extends Controller
{
    public function index()
    {
        UpdateColorsJob::dispatch();
        $events = Evenement::where('user_id', auth()->id())->get();

        // Retourner les événements en format JSON
        return response()->json($events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->titre .' - '. $event->institution,
                'start' => $event->date . 'T' . $event->heure_debut,
                'end' => $event->date . 'T' . $event->heure_fin,
                'color' => $event->color,
            ];
        }));
    }





    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'institution_id' => 'required|exists:institutions,id',
            'filiere_id' => 'required|exists:filieres,id',
            'salle_id' => 'required|exists:salles,id',
            'rappel' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'annee_id' => 'required|exists:annee_academiques,id'
        ]);

        try {
            // Création de l'événement
            $event = new Evenement;
            $event->titre = UniteEnseignement::findOrFail($request->titre_id)->nom;
            $event->date = $request->date;
            $event->heure_debut = $request->heure_debut;
            $event->heure_fin = $request->heure_fin;
            $event->institution_id = $request->institution_id;
            $event->institution = Institution::findOrFail($request->institution_id)->nom;
            $event->filiere = Filiere::findOrFail($request->filiere_id)->nom;
            $event->salle = Salle::findOrFail($request->salle_id)->nom;
            $event->annee_id = $request->annee_id;
            $event->user_id = $request->user_id;
            $event->rappel = $request->rappel;
            $event->fichier = Salle::findOrFail($request->salle_id)->liste;
            $event->save();

            // Dispatch job de notification
            NotifJob::dispatch();

            // Traiter le fichier associé à la salle
            $salle = Salle::findOrFail($request->salle_id);
            $filePath = $salle->liste;

            if (!$filePath) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aucun fichier associé à la salle.',
                ], 404);
            }

            // Déterminer l'extension du fichier
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            if ($extension === 'xml') {
                // Traiter le fichier XML
                $xmlContent = Storage::disk('public')->get($filePath);
                $xml = simplexml_load_string($xmlContent);

                if ($xml === false || !isset($xml->etudiant)) {
                    return response()->json(['error' => 'Structure du fichier XML invalide.'], 400);
                }

                foreach ($xml->etudiant as $etudiant) {
                    $matricule = (string) $etudiant->matricule;
                    $nom = (string) $etudiant->nom;
                    $prenom = (string) $etudiant->prenom;
                    $sexe = (string) $etudiant->sexe;

                    $etudiantRecord = Etudiant::updateOrCreate(
                        ['matricule' => $matricule],
                        ['nom' => $nom, 'prenom' => $prenom, 'sexe' => $sexe]
                    );

                    Presence::updateOrCreate(
                        ['etudiant_id' => $etudiantRecord->id, 'evenement_id' => $event->id]
                    );
                }
            } elseif ($extension === 'xlsx') {
                // Traiter le fichier XLSX
                $spreadsheet = IOFactory::load(storage_path('app/public/' . $filePath));
                $worksheet = $spreadsheet->getActiveSheet();

                // Sauter la première ligne (en-têtes)
                $rowIterator = $worksheet->getRowIterator(2);
                foreach ($rowIterator as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    $data = [];
                    foreach ($cellIterator as $cell) {
                        $data[] = $cell->getValue();
                    }

                    if (count($data) >= 4) {
                        $matricule = $data[0];
                        $nom = $data[1];
                        $prenom = $data[2];
                        $sexe = $data[3];

                        $etudiantRecord = Etudiant::updateOrCreate(
                            ['matricule' => $matricule, 'nom' => $nom, 'prenom' => $prenom],
                            ['sexe' => $sexe],
                        );

                        Presence::updateOrCreate(
                            ['etudiant_id' => $etudiantRecord->id, 'evenement_id' => $event->id]
                        );
                    }
                }

                return response()->json(['success' => true]);
            } else {
                return response()->json(['error' => 'Type de fichier non pris en charge.'], 400);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Gestion des exceptions
            return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
        }
    }






    public function update(Request $request, $id)
    {
        $event = Evenement::findOrFail($id);

        // Mettre à jour les informations de l'événement
        $event->update([
            'titre' => UniteEnseignement::where('id',$request->titre_id)->get()->first()->nom,
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'salle' => Salle::where('id',$request->salle_id)->get()->first()->nom,
            'filiere' => Filiere::where('id',$request->filiere_id)->get()->first()->nom,
            'institution_id' => $request->institution_id,
            'institution' => Institution::where('id', $request->institution_id)->get()->first()->nom ?? 'None',
            'rappel' => $request->rappel,
        ]);



            Presence::where('evenement_id', $id)->delete();

            // Enregistrer le nouveau fichier
            $file = Salle::where('id',$request->salle_id)->get()->first()->liste;
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $path = Salle::where('id',$request->salle_id)->get()->first()->liste;
            $event->fichier = $path;

            if ($extension === 'xml') {
                // Traiter le fichier XML
                $xmlContent = Storage::disk('public')->get($path);
                $xml = simplexml_load_string($xmlContent);

                if ($xml === false || ! isset($xml->etudiant)) {
                    return response()->json(['error' => 'Structure du fichier XML invalide.'], 400);
                }

                foreach ($xml->etudiant as $etudiant) {
                    $matricule = (string) $etudiant->matricule;
                    $nom = (string) $etudiant->nom;
                    $prenom = (string) $etudiant->prenom;
                    $sexe = (string) $etudiant->sexe;

                    $etudiantRecord = Etudiant::updateOrCreate(
                        ['matricule' => $matricule, 'nom' => $nom, 'prenom' => $prenom],
                        ['sexe' => $sexe]
                    );

                    Presence::updateOrCreate(
                        ['etudiant_id' => $etudiantRecord->id, 'evenement_id' => $event->id]
                    );
                }
            } elseif ($extension === 'xlsx') {
                // Traiter le fichier XLSX
                $spreadsheet = IOFactory::load(storage_path('app/public/'.$path));
                $worksheet = $spreadsheet->getActiveSheet();

                // Sauter la première ligne (en-têtes)
                $rowIterator = $worksheet->getRowIterator(2); // Commence à la ligne 2
                foreach ($rowIterator as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    $data = [];
                    foreach ($cellIterator as $cell) {
                        $data[] = $cell->getValue();
                    }

                    if (count($data) >= 4) {
                        $matricule = $data[0];
                        $nom = $data[1];
                        $prenom = $data[2];
                        $sexe = $data[3];

                        $etudiantRecord = Etudiant::updateOrCreate(
                            ['matricule' => $matricule, 'nom' => $nom, 'prenom' => $prenom],
                            ['sexe' => $sexe]
                        );

                        Presence::updateOrCreate(
                            ['etudiant_id' => $etudiantRecord->id, 'evenement_id' => $event->id]
                        );
                    }
                }
            } else {
                return response()->json(['error' => 'Type de fichier non pris en charge.'], 400);
            }


        $event->save();
        Notification::where('evenement_id', $id)->delete();
        NotifJobModif::dispatch($id);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {

        $event = Evenement::find($id);
        if (! $event) {
            return response()->json(['error' => 'Événement non trouvé'], 404);
        }

        $event->delete();

        return response()->json(['success' => true]);
    }

    public function startEvent(Request $request, $id)
    {
        $event = Evenement::find($id);

        if ($event) {
            $event->heure_arrivee = $request->heure_arrivee;
            $event->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Événement non trouvé.'], 404);
    }

    public function show($id)
    {

        // Récupérer l'événement par ID
        $evenement = Evenement::findOrFail($id);
        // Passer les données à la vue
        return view('start', compact('evenement'));

    }

    public function showForModif($id)
    {
        // Rechercher l'événement par ID ou renvoyer une erreur 404 s'il n'est pas trouvé
        $event = Evenement::findOrFail($id);
        $unite = UniteEnseignement::where('nom',$event->titre)->get()->first();
        $salle = Salle::where('nom',$event->salle)->get()->first();
        $filiere = Filiere::where('nom',$event->filiere)->get()->first();

        $data = [
            'event' => $event,
            'unite' => $unite,
            'salle' => $salle,
            'filiere' => $filiere,
        ];

        // Retourner les données de l'événement au format JSON
        return response()->json($data);
    }

    public function storeNotesAndComments(Request $request, $id)
    {
        // Valider les données
        $request->validate([
            'note_plus' => 'nullable|string',
            'note_moins' => 'nullable|string',
            'note_devoir_maison' => 'nullable|string',
            'commentaire' => 'nullable|string',
        ]);

        // Enregistrer les notes
        if ($request->filled('note_plus')) {
            Note::updateOrCreate(
                ['evenement_id' => $id, 'categorie' => 'plus'],
                ['contenu' => $request->input('note_plus')]
            );
        }
        if ($request->filled('note_moins')) {
            Note::updateOrCreate(
                ['evenement_id' => $id, 'categorie' => 'moins'],
                ['contenu' => $request->input('note_moins')]
            );
        }
        if ($request->filled('note_devoir_maison')) {
            Note::updateOrCreate(
                ['evenement_id' => $id, 'categorie' => 'devoir_maison'],
                ['contenu' => $request->input('note_devoir_maison')]
            );
        }

        // Enregistrer le commentaire
        if ($request->filled('commentaire')) {
            Commentaire::updateOrCreate(
                ['evenement_id' => $id],
                ['contenu' => $request->input('commentaire')]
            );
        }

        return redirect()->back()->with('success', 'Notes et commentaires enregistrés avec succès.');
    }

    public function terminer(Request $request, $id)
    {
        UpdateColorsJob::dispatch();

        $evenement = Evenement::findOrFail($id);

        $evenement->heure_depart = $request->input('heure_depart');
        $evenement->duree = $request->input('duree');
        $evenement->save();

        return response()->json(['message' => 'Événement mis à jour avec succès.']);
    }

    public function getStudents($eventId)
    {
        $students = Etudiant::whereIn('id', function ($query) use ($eventId) {
            $query->select('etudiant_id')
                ->from('presences')
                ->where('evenement_id', $eventId);
        })->get();

        return response()->json(['students' => $students]);
    }

    public function getUniversite()
    {
        $universites = Universite::all();
        return response()->json(['universites' => $universites]);
    }

    public function saveAttendance(Request $request, $eventId)
    {
        $statuses = $request->input('statuses');

        foreach ($statuses as $studentId => $status) {
            Presence::where('etudiant_id', $studentId)
                ->where('evenement_id', $eventId)
                ->update(['status' => $status]);
        }

        return response()->json(['success' => true]);
    }

    public function evenements_blue(){

        // Pour les événements du jour
        $aujourdhui = Carbon::today()->toDateString(); // Obtenir la date d'aujourd'hui
        $evenement_jour = Evenement::where('user_id', auth()->id())
            ->where('color', 'blue')
            ->whereDate('date', $aujourdhui)
            ->orderBy('heure_debut', 'asc')
            ->get();

        // Pour les événements de la semaine
        $debutSemaine = Carbon::now()->startOfWeek(); // Début de la semaine (lundi)
        $finSemaine = Carbon::now()->endOfWeek(); // Fin de la semaine (dimanche)

        $evenement_semaine = Evenement::where('user_id', auth()->id())
            ->where('color', 'blue')
            ->whereBetween('date', [$debutSemaine, $finSemaine])
            ->orderBy('date', 'asc')
            ->orderBy('heure_debut', 'asc')
            ->get();

        return view('evenements_blue',[
            'evenement_jour' => $evenement_jour,
            'evenement_semaine' => $evenement_semaine,
        ]);
    }

    function jourAnglaisVersFrancais($jourAnglais)
    {
        $joursFrancais = [
            'Monday'    => 'lundi',
            'Tuesday'   => 'mardi',
            'Wednesday' => 'mercredi',
            'Thursday'  => 'jeudi',
            'Friday'    => 'vendredi',
            'Saturday'  => 'samedi',
            'Sunday'    => 'dimanche'
        ];

        return $joursFrancais[$jourAnglais] ?? $jourAnglais;
    }




    public function creation()
    {
        $userId = auth()->user()->id;
        $anneeActive = AnneeAcademique::where('user_id', $userId)->where('is_active', true)->first();
        $activeId = $anneeActive ? $anneeActive->id : null;

        $uniteEnseignements = UniteEnseignement::where('user_id', $userId)
            ->where('annee_id', $activeId)
            ->get();

        $institutions = Institution::where('user_id', $userId)
            ->where('annee_id', $activeId)
            ->get();

        $salles = Salle::where('user_id', $userId)
            ->where('annee_id', $activeId)
            ->get();

        $filieres = Filiere::where('user_id', $userId)
            ->where('annee_id', $activeId)
            ->get();

        return view('evenements.create', compact('uniteEnseignements', 'institutions', 'salles', 'filieres', 'activeId'));
    }

    public function storage(Request $request)
    {
        $request->validate([
            'titre' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'jours' => 'required|array',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i',
            'institution' => 'required|integer',
            'salle' => 'required|integer',
            'rappel' => 'required|integer',
            'color' => 'string',
            'filiere' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'annee_id' => 'required|exists:annee_academiques,id'
        ]);

        $jours = $request->input('jours');
        $dateDebut = new \DateTime($request->input('date_debut'));
        $dateFin = new \DateTime($request->input('date_fin'));
        $heureDebut = $request->input('heure_debut');
        $heureFin = $request->input('heure_fin');
        $institutionId = $request->input('institution');
        $salleId = $request->input('salle');

        // Récupération du fichier à partir de la salle
        $salle = Salle::find($salleId);
        $fichier = $salle ? $salle->liste : '';

        function jourAnglaisVersFrancais($jourAnglais)
        {
            $joursFrancais = [
                'Monday'    => 'lundi',
                'Tuesday'   => 'mardi',
                'Wednesday' => 'mercredi',
                'Thursday'  => 'jeudi',
                'Friday'    => 'vendredi',
                'Saturday'  => 'samedi',
                'Sunday'    => 'dimanche'
            ];

            return $joursFrancais[$jourAnglais] ?? $jourAnglais;
        }

        $eventIds = []; // Tableau pour stocker les ID des événements

        while ($dateDebut <= $dateFin) {
            $jourEnFrancais = jourAnglaisVersFrancais($dateDebut->format('l'));

            if (in_array($jourEnFrancais, $jours)) {
                $eventId = \DB::table('evenements')->insertGetId([
                    'titre' => $request->input('titre'),
                    'date' => $dateDebut->format('Y-m-d'),
                    'heure_debut' => $heureDebut,
                    'heure_fin' => $heureFin,
                    'institution' => Institution::find($institutionId)->nom,
                    'salle' => Salle::find($salleId)->nom,
                    'rappel' => $request->input('rappel'),
                    'color' => $request->input('color'),
                    'fichier' => $fichier,
                    'filiere' => $request->input('filiere'),
                    'user_id' => $request->input('user_id'),
                    'institution_id' => $institutionId,
                    'annee_id' => $request->input('annee_id'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $eventIds[] = $eventId; // Stocker l'ID de l'événement

                // Dispatch job de notification
                NotifJob::dispatch();

                // Traiter le fichier associé à la salle
                $filePath = $salle->liste;

                if (!$filePath) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Aucun fichier associé à la salle.',
                    ], 404);
                }

                // Déterminer l'extension du fichier
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);

                if ($extension === 'xml') {
                    // Traiter le fichier XML
                    $xmlContent = Storage::disk('public')->get($filePath);
                    $xml = simplexml_load_string($xmlContent);

                    if ($xml === false || !isset($xml->etudiant)) {
                        return response()->json(['error' => 'Structure du fichier XML invalide.'], 400);
                    }

                    foreach ($xml->etudiant as $etudiant) {
                        $matricule = (string) $etudiant->matricule;
                        $nom = (string) $etudiant->nom;
                        $prenom = (string) $etudiant->prenom;
                        $sexe = (string) $etudiant->sexe;

                        $etudiantRecord = Etudiant::updateOrCreate(
                            ['matricule' => $matricule],
                            ['nom' => $nom, 'prenom' => $prenom, 'sexe' => $sexe]
                        );

                        foreach ($eventIds as $eventId) {
                            Presence::updateOrCreate(
                                ['etudiant_id' => $etudiantRecord->id, 'evenement_id' => $eventId]
                            );
                        }
                    }
                } elseif ($extension === 'xlsx') {
                    // Traiter le fichier XLSX
                    $spreadsheet = IOFactory::load(storage_path('app/public/' . $filePath));
                    $worksheet = $spreadsheet->getActiveSheet();

                    // Sauter la première ligne (en-têtes)
                    $rowIterator = $worksheet->getRowIterator(2);
                    foreach ($rowIterator as $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);

                        $data = [];
                        foreach ($cellIterator as $cell) {
                            $data[] = $cell->getValue();
                        }

                        if (count($data) >= 4) {
                            $matricule = $data[0];
                            $nom = $data[1];
                            $prenom = $data[2];
                            $sexe = $data[3];

                            $etudiantRecord = Etudiant::updateOrCreate(
                                ['matricule' => $matricule],
                                ['nom' => $nom, 'prenom' => $prenom, 'sexe' => $sexe],
                            );

                            foreach ($eventIds as $eventId) {
                                Presence::updateOrCreate(
                                    ['etudiant_id' => $etudiantRecord->id, 'evenement_id' => $eventId]
                                );
                            }
                        }
                    }

                } else {
                    return response()->json(['error' => 'Type de fichier non pris en charge.'], 400);
                }
            }

            $dateDebut->modify('+1 day');
        }

        return redirect()->route('evenements.creation')->with('success', 'Action effectuée avec succès.');
    }

}
