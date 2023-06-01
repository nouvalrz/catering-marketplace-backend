<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomChat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chats(){
        return $this->hasMany(Chats::class, 'roomchats_id');
    }

    public function catering(){
        return $this->belongsTo(Catering::class, 'catering_id');
    }

    public function latestChat()
    {
        return $this->hasOne(Chats::class, 'roomchats_id')->latest();
    }
}
