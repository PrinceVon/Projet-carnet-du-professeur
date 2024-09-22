<?php

namespace App\Jobs;

use App\Models\Evenement;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Récupérer tous les événements
        $evenement = Evenement::latest()->first();

        $rappelMinutes = (int) $evenement->rappel;
        $dateHeureDebut = Carbon::createFromFormat('Y-m-d H:i:s', $evenement->date.' '.$evenement->heure_debut);
        $sendAt = $dateHeureDebut->subMinutes($rappelMinutes);

        // Déterminer le message selon la valeur de $evenement->rappel
        if ($evenement->rappel == 10 || $evenement->rappel == 30) {
            $message = "Vous avez un cours nommé \"{$evenement->titre}\" dans l'institution {$evenement->institution} à la salle {$evenement->salle } à {$evenement->heure_debut}, dans {$evenement->rappel} minutes. Filière :  $evenement->filiere .";
        } elseif ($evenement->rappel == 60 || $evenement->rappel == 300) {
            $message = "Vous avez un cours nommé \"{$evenement->titre}\" dans l'institution {$evenement->institution} à la salle {$evenement->salle } à {$evenement->heure_debut}, dans ".($evenement->rappel / 60).' heure(s). Filière :  $evenement->filiere .';
        } else {
            // Valeur par défaut ou autre cas
            $message = "Vous avez un cours nommé \"{$evenement->titre}\" dans l'institution {$evenement->institution} à la salle {$evenement->salle } à {$evenement->heure_debut}, dans {$evenement->rappel} minutes. Filière :  $evenement->filiere .";
        }

        // Créer la notification avec le message déterminé
        Notification::create([
            'user_id' => $evenement->user_id,
            'evenement_id' => $evenement->id,
            'message' => $message,
            'send_at' => $sendAt,
        ]);

    }
}
