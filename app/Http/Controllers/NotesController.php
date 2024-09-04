<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Evenement;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index(){
        $notes_plus_iai = Note::where('categorie', 'plus')
            ->whereHas('evenement', function($query) {
                $query->where('institution_id', 1);
            })
            ->with('evenement')
            ->get();

        $notes_plus_ucao = Note::where('categorie', 'plus')
        ->whereHas('evenement', function($query) {
            $query->where('institution_id', 2);
        })
        ->with('evenement')
        ->get();

        $notes_plus_esg = Note::where('categorie', 'plus')
        ->whereHas('evenement', function($query) {
            $query->where('institution_id', 3);
        })
        ->with('evenement')
        ->get();

        $notes_plus_univ = Note::where('categorie', 'plus')
        ->whereHas('evenement', function($query) {
            $query->where('institution_id', 4);
        })
        ->with('evenement')
        ->get();

        $notes_plus_par = Note::where('categorie', 'plus')
        ->whereHas('evenement', function($query) {
            $query->where('institution_id', 5);
        })
        ->with('evenement')
        ->get();

        $notes_moins_iai = Note::where('categorie', 'moins')
            ->whereHas('evenement', function($query) {
                $query->where('institution_id', 1);
            })
            ->with('evenement')
            ->get();

        $notes_moins_ucao = Note::where('categorie', 'moins')
        ->whereHas('evenement', function($query) {
            $query->where('institution_id', 2);
        })
        ->with('evenement')
        ->get();

        $notes_moins_esg = Note::where('categorie', 'moins')
        ->whereHas('evenement', function($query) {
            $query->where('institution_id', 3);
        })
        ->with('evenement')
        ->get();

        $notes_moins_univ = Note::where('categorie', 'moins')
        ->whereHas('evenement', function($query) {
            $query->where('institution_id', 4);
        })
        ->with('evenement')
        ->get();

        $notes_moins_par = Note::where('categorie', 'moins')
        ->whereHas('evenement', function($query) {
            $query->where('institution_id', 5);
        })
        ->with('evenement')
        ->get();



        return view('notes',[
            'notes_plus_iai' => $notes_plus_iai,
            'notes_plus_esg' => $notes_plus_esg,
            'notes_plus_ucao' => $notes_plus_ucao,
            'notes_plus_univ' => $notes_plus_univ,
            'notes_plus_par' => $notes_plus_par,
            'notes_moins_iai' => $notes_moins_iai,
            'notes_moins_esg' => $notes_moins_esg,
            'notes_moins_ucao' => $notes_moins_ucao,
            'notes_moins_univ' => $notes_moins_univ,
            'notes_moins_par' => $notes_moins_par,
        ]);
    }
}
