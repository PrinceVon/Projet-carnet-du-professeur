<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    use HasFactory;
    protected $fillable = ['annee_scolaire', 'user_id', 'is_active'];
    protected $table = 'annee_academiques';

}
