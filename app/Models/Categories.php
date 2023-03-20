<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
//
//    public function catering_categories(){
//        return $this->hasMany(CateringCategories::class, "categories_id");
//    }
}
