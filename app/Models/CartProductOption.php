<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProductOption extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function option_choice(){
        return $this->belongsTo(ProductOptionDetail::class, 'product_option_detail_id');
    }
}
