<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product_option_details()
    {
        return $this->hasMany(ProductOptionDetail::class, 'product_options_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
