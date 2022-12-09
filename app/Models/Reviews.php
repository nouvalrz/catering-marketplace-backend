<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'customer_id',
        'star',
        'has_image',
        'description',
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class, 'foreign_key');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'foreign_key');
    }
}
