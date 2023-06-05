<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintImages extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'complaint_id',
        'image',
    ];

    public function complaints()
    {
        return $this->belongsToMany(Complaints::class, 'complaint_id');
    }

    public function getImageAttribute($image)
    {
        return asset('storage/complaints/' . $image);
    }
    
}
