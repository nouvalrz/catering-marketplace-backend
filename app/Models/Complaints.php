<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'foreign_key');
    }

    public function images(){
        return $this->hasMany(ComplaintImage::class, 'complaint_id');
    }
}
