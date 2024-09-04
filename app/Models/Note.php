<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['evenement_id', 'categorie', 'contenu'];


    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'evenement_id');
    }
}
