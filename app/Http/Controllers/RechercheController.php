<?php
namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Salle;
use App\Models\Filiere;
use App\Models\Evenement;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use App\Models\UniteEnseignement;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->user()->id;
        $anneeActive = AnneeAcademique::where('user_id',auth()->user()->id)->where('is_active',true)->get()->first();
        // Récupérer les données des listes déroulantes
        $institutions = Institution::where('user_id', $userId)
            ->where('annee_id', $anneeActive->annee_scolaire)
            ->get();

        $filieres = Filiere::where('user_id', $userId)
            ->where('annee_id', $anneeActive->annee_scolaire)
            ->get();

        $unites = UniteEnseignement::where('user_id', $userId)
        ->where('annee_id', $anneeActive->annee_scolaire)
        ->get();

        $salles = Salle::where('user_id', $userId)
            ->where('annee_id', $anneeActive->annee_scolaire)
            ->get();

        // Filtrer les événements en fonction des critères
        $evenementsQuery = Evenement::query();

        if ($request->filled('institution')) {
            $evenementsQuery->where('institution_id', $request->input('institution'));
        }

        if ($request->filled('filiere')) {
            $evenementsQuery->where('filiere', $request->input('filiere'));
        }

        if ($request->filled('salle')) {
            $evenementsQuery->where('salle', $request->input('salle'));
        }

        $evenements = $evenementsQuery->get();

        // Trouver les notes associées aux événements filtrés
        $notes = Note::whereIn('evenement_id', $evenements->pluck('id'))
            ->where('categorie', 'devoir_maison')
            ->with('evenement') // Charger la relation pour l'affichage
            ->orderBy('created_at', 'asc') // Filtrer du moins récent au plus récent
            ->get();

        return view('recherche', compact('notes', 'institutions', 'filieres', 'salles', 'unites'));
    }

}
