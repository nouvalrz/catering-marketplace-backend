<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'catering_id',
        'customer_id',
        'star',
        'has_image',
        'description',
    ];

    // protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::created(function($review){
            // ... code here
            $cateringAvgRate = Reviews::where('catering_id', $review->catering_id)->avg('star');
            if($cateringAvgRate != 0.0 || $cateringAvgRate != 0){
                $catering = Catering::find($review->catering_id);
                $catering->rate = $cateringAvgRate;
                $catering->save();
            }
        });

    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function getHasImageAttribute($has_image)
    {
        return asset('storage/reviews/' . $has_image);
    }





//    public function user(){
//        return $this->customer()->user();
//    }
}
