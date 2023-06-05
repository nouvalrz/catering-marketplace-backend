<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public function product()
    {
        return $this->belongsToMany(Product::class);
    }

    public function categoryCatering()
    {
        return $this->hasMany(CateringCategories::class, 'categorie_id');
    }
}
