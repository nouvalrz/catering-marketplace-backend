<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;


    public function catering()
    {
        return $this->belongsTo(Catering::class, 'foreign_key');
    }

    public function scopeActive($query){
        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        return $query->whereDate('start_date', '>=', $date)->orWhereDate('end_date', '<=', $date);
    }
    
    protected $fillable = [
        'catering_id',
        'title',
        'description',
        'percentage',
        'minimum_spend',
        'maximum_disc',
        'start_date',
        'end_date'

    ];

    // public function catering()
    // {
    //     return $this->belongsTo(Catering::class, 'foreign_key');
    // }
}
