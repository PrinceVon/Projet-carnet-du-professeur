<?php
namespace App\Helpers;
use App\Models\AnneeAcademique;

if (! function_exists('activeId')) {
    /**
     * Récupère l'ID de l'année académique active pour l'utilisateur authentifié.
     *
     * @return int|null
     */
    function activeId()
    {
        return AnneeAcademique::where('is_active', true)
                                          ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                                          ->pluck('id')
                                          ->first();
    }
}
