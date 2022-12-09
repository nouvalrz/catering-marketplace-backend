<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    use HasFactory;

    protected $fillable = [
        'catering_id',
        'type',
        'title',
        'description',
        'percentage',
        'minimum_spend',
        'maximum_disc',
        'start_date',
        'end_date'

    ];

    public function catering()
    {
        return $this->belongsTo(Catering::class, 'foreign_key');
    }
}
