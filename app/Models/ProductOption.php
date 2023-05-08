<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'option_name',
        'option_type',
        'minimum_selection',
        'maximum_selection',
        'is_active',
    ];

    public function optionDetail()
    {
        return $this->hasMany(ProductOptionDetail::class, 'product_options_id');
    }

    // public function getImageAttribute($image)
    // {
    //     return asset('storage/products/' . $image);
    // }
}
