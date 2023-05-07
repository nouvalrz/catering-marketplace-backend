<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catering extends Model
{
    use HasFactory;

    protected $guarded = [
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'foreign_key');
    }

    public function categories(){
        return $this->belongsToMany(Categories::class, "catering_categories");
    }


    public function products(){
        return $this->hasMany(Product::class);
    }

    public function recommendation_products(){
        return $this->hasMany(Product::class)->orderByDesc("total_sales");
    }

    public function village(){
        return $this->belongsTo(Village::class);
    }
}
