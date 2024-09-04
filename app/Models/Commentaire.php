<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $fillable = ['evenement_id', 'contenu'];


    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }
}
