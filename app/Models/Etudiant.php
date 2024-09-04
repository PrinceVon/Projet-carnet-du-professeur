<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = ['matricule','nom', 'prenom', 'sexe'];

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function evenements()
    {
        return $this->belongsToMany(Evenement::class, 'presences');
    }
}
