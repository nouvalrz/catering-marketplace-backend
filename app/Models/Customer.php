<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'balance',
        'points',
        'phone',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'foreign_key');
    }
    
    public function getImageAttribute($image)
    {
        return asset('storage/customers/' . $image);
    }
}
