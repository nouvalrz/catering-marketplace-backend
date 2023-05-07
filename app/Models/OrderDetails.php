<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        parent::boot();
        static::created(function ($orderDetail) {
            //
            $product = Product::find($orderDetail->product_id);
            $catering = Catering::find($product->catering_id);
            $product->total_sales += $orderDetail->quantity;
            $catering->total_sales += $orderDetail->quantity;
            $product->save();
            $catering->save();
        });
    }



    public function orders()
    {
        return $this->belongsTo(Orders::class, 'foreign_key');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productOptions(){
        return $this->hasMany(OrderProductOption::class, 'order_details_id');
    }
}
