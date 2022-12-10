<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catering extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'description',
        'phone',
        'address',
        'village_id',
        'zipcode',
        'latiude',
        'longtitude',
        'delivery_start_time',
        'delivery_end_time',
        'image_id',
        'isVerified',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'foreign_key');
    }
}
