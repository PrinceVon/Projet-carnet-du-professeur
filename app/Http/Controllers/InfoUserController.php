<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Evenement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


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

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Supprimez l'ancienne photo si nécessaire
            $oldPhotoPath = public_path($user->photo);
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }

            // Récupérer le fichier photo
            $photo = $request->file('photo');

            // Créer un nom de fichier personnalisé
            $filename = time() . '-' . $photo->getClientOriginalName();

            // Déplacer le fichier vers le répertoire public/assets/img
            $photo->move(public_path('assets/img'), $filename);

            // Mettez à jour le chemin de l'image dans la base de données
            $user->update(['photo' => 'assets/img/' . $filename]);
        }

        return back()->with('success', 'Photo mise à jour avec succès.');
    }







}
