<?php

namespace App\Models;

use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    use HasFactory;

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    protected $fillable = ['etudiant_id', 'evenement_id', 'status'];

    protected $table = 'Presences';
}
