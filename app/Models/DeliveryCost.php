<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCost extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'catering_id',
        'cost',
        'min_distance'

    ];

    public function catering()
    {
        return $this->belongsTo(Catering::class, 'foreign_key');
    }
}
