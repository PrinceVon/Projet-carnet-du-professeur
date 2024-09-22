<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Filiere;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use App\Models\UniteEnseignement;
use App\Http\Controllers\Controller;

class ProfController extends Controller
{
    public function index(){
        $anneeActive=AnneeAcademique::where('user_id', auth()->user()->id)->where('is_active',true)->get()->first();
        if($anneeActive != null){
            $activeId =$anneeActive->id;
            $anneesAcademiques = AnneeAcademique::where('user_id', auth()->user()->id)->get();
            $unitesEnseignement = UniteEnseignement::where('user_id', auth()->user()->id)->where('annee_id',$activeId)->get();
            $filieres=Filiere::where('user_id', auth()->user()->id)->where('annee_id',$activeId)->get();
            $institutions=Institution::where('user_id',auth()->user()->id)->where('annee_id',$activeId)->get();
            $salles=Salle::where('user_id',auth()->user()->id)->where('annee_id',$activeId)->get();
            return view('dashboard_prof', compact('anneesAcademiques', 'anneeActive','unitesEnseignement','institutions','salles', 'activeId', 'filieres'));
        }
        else{
            return view('dashboard_prof', compact('anneeActive'));
        }

    }
}
