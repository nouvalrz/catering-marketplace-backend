<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'status',
        'method',
    ];

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'foreign_key');
    }
}
