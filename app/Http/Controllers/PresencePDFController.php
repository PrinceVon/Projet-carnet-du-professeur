<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Barryvdh\DomPDF\Facade\Pdf;

class PresencePDFController extends Controller
{
    public function generatePDF($evenementId)
    {
        // Récupérer l'événement et ses présences
        $evenement = Evenement::with('presences.etudiant')->find($evenementId);

        if (! $evenement) {
            return abort(404, 'Événement non trouvé');
        }

        $presences = $evenement->presences;

        // Passer les données à la vue
        $pdf = Pdf::loadView('pdf.presences', compact('evenement', 'presences'));

        // Télécharger le PDF
        return $pdf->download('presence_cours_'.$evenement->id.'.pdf');
    }

    public function sansPDF($evenementId)
    {
        $evenement = Evenement::with('presences.etudiant')->find($evenementId);
        $presences = $evenement->presences;

        return view('presence', compact('evenement', 'presences'));

    }
}
