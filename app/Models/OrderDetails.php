<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'package_number',
        'delivery_datetime',
        'status',
    ];

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'foreign_key');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
