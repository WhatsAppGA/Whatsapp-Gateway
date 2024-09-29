<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'sender', 'name', 'phonebook_id', 'type', 'status', 'message', 'schedule','delay'];

    public function blasts(){
        return $this->hasMany(Blast::class);
    }

    public function phonebook(){
        return $this->belongsTo(Tag::class);
    }

    public function device(){
        return $this->belongsTo(Device::class);
    }

    public function scopeFilter ($query, $request)
    {
        return $query->when($request->device , function($q) use ($request){
            return $q->whereHas('device', function($q) use ($request){
                return $q->where('body','=', $request->device);
            });
        })->when($request->status , function($q) use ($request){
            if ($request->status == 'all') {
                return $q;
            } else {
                return $q->where('status','=', $request->status);
            }
        });
    }


    public function getScheduleAttribute($value){
        return $value ? date('d M y H:i', strtotime($value)) : null;
    }

    
}
