<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    use HasFactory;

    protected $fillable = [
        'orders_id',
        'status',
        'problem',
        'solution_type'
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

    public function images(){
        return $this->hasMany(ComplaintImage::class, 'complaint_id');
    }
}
