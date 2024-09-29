<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'body', 'webhook', 'status', 'message_sent'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function autoreplies()
    {
        return $this->hasMany(Autoreply::class, 'device', 'body');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }


    
}
