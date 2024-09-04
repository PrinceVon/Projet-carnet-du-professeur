<?php

namespace App\Console;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\eMAIL;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:colors')->Hourly(); // Exemple : toutes les minutes

        $schedule->call(function () {
            $now = now();

            $notifications = Notification::where('send_at', '<=', $now)->get();

            foreach ($notifications as $notification) {
                $user = User::find($notification->user_id);
                if ($user) {
                    $user->notify(new eMAIL($notification->message));
                    // Supprimer la notification après envoi
                    $notification->delete();
                }
            }
        })->everyMinute(); // Vérifier toutes les minutes
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    // app/Console/Kernel.php

}
