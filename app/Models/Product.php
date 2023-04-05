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
        'description',
        'weight',
        'price',
        'minimum_quantity',
        'maximum_quantity',
        'is_free_delivery',
        'is_hidden',
        'is_available',
        'image_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(Categories::class)->withTimestamps();
    }
}
