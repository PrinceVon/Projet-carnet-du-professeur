<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'user_id', 'annee_id'];
    protected $table = 'filieres';

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
