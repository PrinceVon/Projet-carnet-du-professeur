<?php

namespace App\Models;

use App\Models\Note;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Commentaire;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evenement extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function commentaire()
    {
        return $this->hasOne(Commentaire::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'presences');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    protected $fillable = [
        'titre',
        'date',
        'heure_debut',
        'heure_fin',
        'institution',
        'salle',
        'rappel',
        'user_id',
        'institution_id',
        'heure_arrivee',
        'heure_depart',
        'duree',
        'fichier',
        'annee_id',
        'filiere',

    ];

    protected $table = 'evenements';

}
