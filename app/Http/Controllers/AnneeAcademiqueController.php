<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use App\Http\Controllers\Controller;

class AnneeAcademiqueController extends Controller
{
    public function update(Request $request)
    {
        $selectedAnneeId = $request->annee_scolaire;

        // Désactiver toutes les années
        anneeacademique::where('user_id', auth()->user()->id)->update(['is_active' => false]);
        // Activer l'année sélectionnée


        AnneeAcademique::where('id', $selectedAnneeId)->update(['is_active' => true]);


        return redirect()->back()->with('success', 'Année scolaire mise à jour avec succès.');
        // return response()->json($data);
    }
}
