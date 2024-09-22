<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65535',
        ]);

        Message::create([
            'message' => $request->input('message'),
            'user_id' => auth()->id(), // Assurez-vous que l'utilisateur est authentifié
        ]);

        return response()->json(['success' => 'Message envoyé avec succès!']);

    }
}
