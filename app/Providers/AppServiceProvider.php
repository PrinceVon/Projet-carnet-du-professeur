<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\AnneeAcademique;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('fr');
        if (auth()->check()) {
            $userId = auth()->user()->id;
            $active = AnneeAcademique::where('user_id', $userId)
                                               ->where('is_active', true)
                                               ->get();

            // Partager les données avec toutes les vues
            view()->share('active', $active);
        }
    }
}
