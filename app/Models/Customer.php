<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'balance',
        'points',
        'phone',
        'image',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders(){
        return $this->hasMany(Orders::class, 'customer_id');
    }

    public function getImageAttribute($image)
    {
        return asset('storage/customers/' . $image);
    }

    public function notifications(){
        return $this->hasMany(CustomerNotification::class, 'customer_id');
    }
}
