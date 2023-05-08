<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'catering_id',
        'category_id',
        'description',
        'weight',
        'price',
        'minimum_quantity',
        'maximum_quantity',
        'is_available',
        'image',
    ];

    public function categories()
    {
        return $this->belongsToMany(Categories::class)->withTimestamps();
    }

    public function getImageAttribute($image)
    {
        return asset('storage/products/' . $image);
    }
}
