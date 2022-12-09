<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringCategories extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'catering_id',
        'category_id'
    ];
    
    public function catering()
    {
        return $this->belongsTo(Catering::class, 'foreign_key');
    }
    public function categories()
    {
        return $this->belongsTo(Categories::class, 'foreign_key');
    }
}
