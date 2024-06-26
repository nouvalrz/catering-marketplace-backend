<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionDetail extends Model
{
    use HasFactory;

    // protected $guarded = [];

    public function product_option()
    {
        return $this->belongsTo(ProductOption::class, 'product_options_id');
    }
    protected $fillable = [
        'product_options_id',
        'option_choice_name',
        'additional_price',
        'is_available',
    ];

    // public function categories()
    // {
    //     return $this->belongsToMany(Categories::class)->withTimestamps();
    // }

    // public function getImageAttribute($image)
    // {
    //     return asset('storage/products/' . $image);
    // }
}
