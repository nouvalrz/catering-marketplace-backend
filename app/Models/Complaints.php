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
}
