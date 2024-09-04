<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee_academique;

class ProfesseurController extends Controller
{
    public function index(){
        $anneesAcademiques = Annee_academique::where('user_id', auth()->user()->id)->get();
        $active=Annee_academique::where('user_id', auth()->user()->id)->where('is_active',true)->get->first();
        return view('dashboard-du-prof', compact('anneesAcademiques'));
    }
}
