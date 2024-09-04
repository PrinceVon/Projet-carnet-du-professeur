<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Filiere;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use App\Models\UniteEnseignement;
use App\Http\Controllers\Controller;

class AjouterController extends Controller
{
    public function annee(){
        return view('ajouter_annee');
    }
    public function annee_store(Request $request){
        // Validation des données
        $request->validate([
            'annee_scolaire' => 'required|string|size:9',
            'user_id' => 'required|exists:users,id',
        ]);

        // Vérifier si l'année scolaire et l'ID utilisateur existent déjà
        $exists = AnneeAcademique::where('annee_scolaire', $request->annee_scolaire)
                                  ->where('user_id', $request->user_id)
                                  ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette année scolaire est déjà enregistrée pour cet utilisateur.',
            ]);
        }

        // Création d'une nouvelle année académique
        AnneeAcademique::create([
            'annee_scolaire' => $request->annee_scolaire,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Année scolaire enregistrée avec succès !',
        ]);
    }


    public function institution(){
        $anneesAcademiques = AnneeAcademique::where('user_id', auth()->user()->id)->get();
        return view('ajouter_institution', ['anneesAcademiques' => $anneesAcademiques]);
    }
    public function institution_store(Request $request){
        // Validation des données
        $request->validate([
            'annee_id' => 'required|exists:annee_academiques,id',
            'nom' => 'required|string|max:255',
            'tarification' => 'required|integer',
            'user_id' => 'required|exists:users,id',
        ]);

        // Vérifier si l'institution existe déjà
        $exists = Institution::where('annee_id', $request->annee_id)
                              ->where('user_id', $request->user_id)
                              ->where('nom', $request->nom)
                              ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette institution est déjà enregistrée pour l\'année académique et l\'utilisateur spécifiés.',
            ]);
        }

        // Création d'une nouvelle institution
        Institution::create([
            'annee_id' => $request->annee_id,
            'nom' => $request->nom,
            'tarification' => $request->tarification,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Institution enregistrée avec succès !',
        ]);
    }

    public function unite_enseignement(){
        $anneesAcademiques = AnneeAcademique::where('user_id', auth()->user()->id)->get();
        return view('ajouter_unite_enseignement', ['anneesAcademiques' => $anneesAcademiques]);
    }
    public function unite_enseignement_store(Request $request){
        // Validation des données
        $request->validate([
            'annee_id' => 'required|exists:annee_academiques,id',
            'nom' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        // Vérifier si l'unité d'enseignement existe déjà
        $exists = UniteEnseignement::where('annee_id', $request->annee_id)
                                    ->where('user_id', $request->user_id)
                                    ->where('nom', $request->nom)
                                    ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette unité d\'enseignement est déjà enregistrée pour l\'année académique et l\'utilisateur spécifiés.',
            ]);
        }

        // Création d'une nouvelle unité d'enseignement
        UniteEnseignement::create([
            'annee_id' => $request->annee_id,
            'nom' => $request->nom,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Unité d\'enseignement enregistrée avec succès !',
        ]);
    }

    public function filiere(){
        $anneesAcademiques = AnneeAcademique::where('user_id', auth()->user()->id)->get();
        return view('ajouter_filiere', ['anneesAcademiques' => $anneesAcademiques]);
    }
    public function filiere_store(Request $request){
        // Validation des données
        $request->validate([
            'annee_id' => 'required|exists:annee_academiques,id',
            'nom' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        // Vérifier si la filière existe déjà
        $exists = Filiere::where('annee_id', $request->annee_id)
                         ->where('user_id', $request->user_id)
                         ->where('nom', $request->nom)
                         ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette filière est déjà enregistrée pour l\'année académique et l\'utilisateur spécifiés.',
            ]);
        }

        // Création d'une nouvelle filière
        Filiere::create([
            'annee_id' => $request->annee_id,
            'nom' => $request->nom,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Filière enregistrée avec succès !',
        ]);
    }


    public function salle(){
        $institutions = Institution::where('user_id', auth()->user()->id)->get();
        $anneesAcademiques = AnneeAcademique::where('user_id', auth()->user()->id)->get();
        return view('ajouter_salle', [
            'institutions' => $institutions,
            'anneesAcademiques' => $anneesAcademiques
        ]);
    }

    public function salle_store(Request $request)
{
    // Validation des données
    $request->validate([
        'nom' => 'required|string|max:255',
        'institution_id' => 'required|exists:institutions,id',
        'annee_id' => 'required|exists:annee_academiques,id',
        'user_id' => 'required|exists:users,id',
        'liste' => 'required|file|mimes:xls,xlsx,xml|max:2048',
    ]);

    // Gestion du fichier
    if ($request->hasFile('liste')) {
        $file = $request->file('liste');
        $filePath = $file->storeAs('files', time().'-'.$file->getClientOriginalName(), 'public');
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Aucun fichier n\'a été sélectionné.',
        ]);
    }

    // Vérifier si une salle avec les mêmes attributs existe déjà
    $existingSalle = Salle::where('nom', $request->nom)
        ->where('institution_id', $request->institution_id)
        ->where('liste', $filePath)
        ->get()->first();

    if ($existingSalle) {
        return response()->json([
            'status' => 'error',
            'message' => 'Une salle avec ces informations existe déjà.',
        ]);
    }

    $differentFichier = Salle::where('nom', $request->nom)
        ->where('institution_id', $request->institution_id)
        ->first();

    if ($differentFichier) {
        return response()->json([
            'status' => 'error',
            'message' => 'La même salle ne peut pas avoir 2 fichiers différents.',
        ]);
    }

    // Création d'une nouvelle salle en utilisant le modèle Salle
    Salle::create([
        'nom' => $request->nom,
        'institution_id' => $request->institution_id,
        'annee_id' => $request->annee_id,
        'user_id' => $request->user_id,
        'liste' => $filePath, // Chemin du fichier stocké
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Salle enregistrée avec succès !',
    ]);
}


}
