<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    public function evenements()
    {
        return $this->hasMany(Evenement::class);
    }

    protected $fillable = ['nom', 'tarification', 'annee_id', 'user_id'];


    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

