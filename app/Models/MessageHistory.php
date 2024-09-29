<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'message',
        'user_id',
        'message',
        'number',
        'send_by',
        'payload',
        'status',
        'type',
        'note',
    ];

    public function device(){
        return $this->belongsTo(Device::class);
    }


}
