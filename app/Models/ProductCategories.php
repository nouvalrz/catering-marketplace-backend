<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'category_id'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'foreign_key');
    }
    public function categories()
    {
        return $this->belongsTo(Categories::class, 'foreign_key');
    }
}
