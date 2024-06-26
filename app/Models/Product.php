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

    // protected $guarded = [];

    //Add extra attribute
//    protected $attributes = ['original_path'];

    //Make it available in the json response
//    protected $appends = ['original_path'];
//
////    protected $guarded = ['original_path'];
//
//    public function getOriginalPathAttribute(){
//        return ltrim(Image::find($this->image_id)->original_path, "\\");
//    }

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

    public function categories()
    {
        return $this->belongsToMany(Categories::class)->withTimestamps();
    }

    public function getImageAttribute($image)
    {
        return asset('storage/products/' . $image);
    }
    
    public function categoryProduct()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
