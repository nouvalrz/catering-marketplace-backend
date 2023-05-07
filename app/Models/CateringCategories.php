<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringCategories extends Model
{
    use HasFactory;

    protected $guarded = [];

//    public function catering()
//    {
//        return $this->belongsTo(Catering::class, 'catering_id');
//    }
//    public function category()
//    {
//        return $this->belongsTo(Categories::class, 'categories_id');
//    }
}
