<?php

namespace App\Jobs;

use App\Models\Evenement;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateColorsJob implements ShouldQueue
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
        $now = Carbon::now();
        $evenements = Evenement::all();

        foreach ($evenements as $evenement) {
            $finEvenement = Carbon::createFromFormat('Y-m-d H:i:s', $evenement->date.' '.$evenement->heure_fin);

            if ($evenement->duree == 0 && $now->greaterThan($finEvenement)) {
                $evenement->color = 'red';
            } elseif ($evenement->duree != 0) {
                $evenement->color = 'green';
            }

            $evenement->save();
        }


    }
}
