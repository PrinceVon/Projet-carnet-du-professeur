<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Evenement;
use App\Models\Commentaire;

class ConsulterController extends Controller
{
    public function index($eventId)
    {
        $event = Evenement::with('presences.etudiant')->find($eventId);
        $presences = $event->presences;
        $notes = Note::where('evenement_id', $eventId)->get();
        $commentaire = Commentaire::where('evenement_id', $eventId)->get();

        return view('consulter', ['event' => $event, 'presences' => $presences, 'notes' => $notes, 'commentaire' => $commentaire]);
    }
}
