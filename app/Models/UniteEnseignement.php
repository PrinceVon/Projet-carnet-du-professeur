<?php

namespace App\Models;

use App\Models\User;
use App\Models\AnneeAcademique;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UniteEnseignement extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'user_id', 'annee_id'];
    protected $table = 'unite_enseignements';

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
