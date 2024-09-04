<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom', 'institution_id', 'annee_id', 'user_id', 'liste',
    ];
}
