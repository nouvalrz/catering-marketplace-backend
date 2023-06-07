<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catering extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'description',
        'phone',
        'address',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'zipcode',
        'latitude',
        'longitude',
        'delivery_start_time',
        'delivery_end_time',
        'image',
        'isVerified',
        'user_id',
        'rate',
        'total_sales',
        'workday',
        'delivery_cost',
        'min_distance_delivery',
        'is_open',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'foreign_key');
    }

    public function getImageAttribute($image)
    {
        if($image){
            return asset('storage/caterings/' . $image);
        }else{
            return null;
        }
    }

    public function categoryCatering()
    {
        return $this->hasMany(CateringCategories::class, 'catering_id');
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

    public function discounts(){
        return $this->hasMany(Discount::class, 'catering_id');
    }

    public function productCatering()
    {
        return $this->hasMany(Product::class, 'catering_id');
    }

    // PUNYA BAGAS
    // public function recommendation_products(){
    //     return $this->hasMany(Product::class, 'catering_id')->orderByDesc("total_sales");
    // }

    public function district(){
        return $this->belongsTo(District::class, 'district_id');
    }
    // protected function data(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => json_decode($value, true),
    //         set: fn ($value) => json_encode($value),
    //     );
    // }
}
