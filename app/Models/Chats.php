<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    use HasFactory;

    protected $fillable = [
        'roomchats_id',
        'message',
        'sender',
    ];

    
    public function room()
    {
        // $rooms = $this->belongsTo(RoomChats::class, 'roomchats_id');
        // return $rooms->belongsTo(Catering::class, 'catering_id');
        return $this->belongsTo(RoomChats::class, 'roomchats_id');
    }
    
}
