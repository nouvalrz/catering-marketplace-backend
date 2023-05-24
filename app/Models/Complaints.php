<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'status',
        'problem',
    ];

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'orders_id');
    }
    
    public function complaintImages()
    {
        return $this->hasMany(ComplaintImages::class, 'complaint_id');
    }
    
    public function getImageAttribute($image)
    {
        return asset('storage/complaints/' . $image);
    }
}
