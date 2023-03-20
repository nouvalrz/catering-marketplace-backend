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
        'image_id'
    ];

    //Add extra attribute
//    protected $attributes = ['original_path'];

    //Make it available in the json response
    protected $appends = ['original_path'];

//    protected $guarded = ['original_path'];

    public function getOriginalPathAttribute(){
        return ltrim(Image::find($this->image_id)->original_path, "\\");
    }

    public function product_options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function catering(){
        return $this->belongsTo(Catering::class);
    }

    public function scopeTop($query){
        $query->orderByDesc("total_sales")->limit(2);
    }
}
