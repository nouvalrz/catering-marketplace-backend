<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomChats extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'catering_id',
        'updated_at'
    ];

    
    public function chats()
    {
        return $this->hasMany(Chats::class, 'roomchats_id');
    }

    public function latestChat()
    {
        return $this->hasOne(Chats::class, 'roomchats_id')->latest();
    }
    
    public function imageCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function imageCatering()
    {
        return $this->belongsTo(Catering::class, 'catering_id');
    }


}
