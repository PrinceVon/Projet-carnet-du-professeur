<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{


    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Gérer le téléchargement
        if ($request->hasFile('photo')) {
            // Supprimez l'ancienne photo si nécessaire
            Storage::delete($user->photo);
            $path = $request->file('photo')->store('profile_photos');
            $user->photo = $path;
            $user->save();
        }

        return response()->json(['success' => 'Photo mise à jour avec succès.']);
    }

}
