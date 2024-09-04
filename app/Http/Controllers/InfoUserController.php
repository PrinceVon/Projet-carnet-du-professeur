<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class InfoUserController extends Controller
{

    public function create()
    {
        // Récupération des événements pour chaque couleur
        $events_blue = Evenement::where('user_id', auth()->id())
        ->where('color', 'blue')
        ->orderBy('date', 'asc')
        ->orderBy('heure_debut', 'asc')
        ->get();

        $events_green = Evenement::where('user_id', auth()->id())
        ->where('color', 'green')
        ->orderBy('date', 'asc')
        ->orderBy('heure_debut', 'asc')
        ->get();

        $events_red = Evenement::where('user_id', auth()->id())
        ->where('color', 'red')
        ->orderBy('date', 'asc')
        ->orderBy('heure_debut', 'asc')
        ->get();

        // Passer les données à la vue
        return view('laravel-examples/user-profile', [
            'events_blue' => $events_blue,
            'events_green' => $events_green,
            'events_red' => $events_red
        ]);
    }

    public function find($id)
    {
        $user = User::where('id',$id)->get()->first();
        // Récupération des événements pour chaque couleur
        $events_blue = Evenement::where('user_id', $id)
        ->where('color', 'blue')
        ->orderBy('date', 'asc')
        ->orderBy('heure_debut', 'asc')
        ->get();

        $events_green = Evenement::where('user_id', $id)
        ->where('color', 'green')
        ->orderBy('date', 'asc')
        ->orderBy('heure_debut', 'asc')
        ->get();

        $events_red = Evenement::where('user_id', $id)
        ->where('color', 'red')
        ->orderBy('date', 'asc')
        ->orderBy('heure_debut', 'asc')
        ->get();

        // Passer les données à la vue
        return view('info_user', [

            'events_blue' => $events_blue,
            'events_green' => $events_green,
            'events_red' => $events_red,
            'user' => $user,
        ]);
    }





}
