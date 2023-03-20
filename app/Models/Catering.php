<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catering extends Model
{
    use HasFactory;
//    public $preventsLazyLoading = true;
//    protected $with = ['recommendation_products'];
    protected $fillable = [
        'name',
        'description',
        'email',
        'phone',
        'address',
        'zipcode',
        'latitude',
        'longitude',
        'delivery_start_time',
        'delivery_end_time',
        'image_id',
        'village_id',
        'isVerified',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'foreign_key');
    }

    public function categories(){
        return $this->belongsToMany(Categories::class, "catering_categories");
    }

    //Make it available in the json response
    protected $appends = ['original_path'];

//    protected $guarded = ['original_path'];

    public function getOriginalPathAttribute(){
        return ltrim(Image::find($this->image_id)->original_path, "\\");
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
