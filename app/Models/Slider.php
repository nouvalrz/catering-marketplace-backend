<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'image',
        'is_active',
    ];

    
    public function getImageAttribute($image)
    {
        return asset('storage/sliders/' . $image);
    }
    

}
