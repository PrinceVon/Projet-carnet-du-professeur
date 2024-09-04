<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'evenement_id', 'message', 'send_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
