<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index(){
        $users = User::all();

        return view('laravel-examples.user-management',[
            'users' => $users,
        ]);
    }
    public function toggleActive(User $user)
    {
        // Assurez-vous que l'utilisateur authentifié est un admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Action non authorisée.');
        }

        // Bascule l'état du compte
        $user->is_active = ! $user->is_active;
        $user->save();

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }


}
