<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function catering()
    {
        return $this->belongsTo(Catering::class, 'foreign_key');
    }

    public function scopeActive($query){
        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        return $query->whereDate('start_date', '>=', $date)->orWhereDate('end_date', '<=', $date);
    }
}
