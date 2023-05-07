<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [
    ];

    public function cart_details(){
        return $this->hasMany(CartDetail::class, 'cart_id');
    }

    public function catering(){
        return $this->belongsTo(Catering::class, 'catering_id');
    }


}
