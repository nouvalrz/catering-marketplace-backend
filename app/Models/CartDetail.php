<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    protected $guarded = [
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_options(){
        return $this->hasMany(CartProductOption::class, 'cart_detail_id');
    }
}
