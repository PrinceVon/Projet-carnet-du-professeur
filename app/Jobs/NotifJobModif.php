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

class NotifJobModif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Récupérer tous les événements
        $evenement = Evenement::find($this->id);
        if (! $evenement) {
            // Gérez le cas où l'événement n'est pas trouvé
            return;
        }

        $rappelMinutes = (int) $evenement->rappel; // Assurez-vous que c'est un entier
        $dateHeureDebut = Carbon::createFromFormat('Y-m-d H:i:s', $evenement->date.' '.$evenement->heure_debut);
        $sendAt = $dateHeureDebut->subMinutes($rappelMinutes);

        // Déterminer le message selon la valeur de $evenement->rappel
        if ($evenement->rappel == 10 || $evenement->rappel == 30) {
            $message = "Vous avez un cours nommé \"{$evenement->titre}\" dans l'institution {$evenement->institution} à {$evenement->heure_debut}, dans {$evenement->rappel} minutes.";
        } elseif ($evenement->rappel == 60 || $evenement->rappel == 300) {
            $message = "Vous avez un cours nommé \"{$evenement->titre}\" dans l'institution {$evenement->institution} à {$evenement->heure_debut}, dans ".($evenement->rappel / 60).' heure(s).';
        } else {
            // Valeur par défaut ou autre cas
            $message = "Vous avez un cours nommé \"{$evenement->titre}\" dans l'institution {$evenement->institution} à {$evenement->heure_debut}, dans {$evenement->rappel} minutes.";
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
