<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionDetail extends Model
{
    use HasFactory;

    public function product_option()
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }
}
