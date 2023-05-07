<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function province()
    {
        return $this->belongsTo(Province::class, 'foreign_key');
    }
    public function district()
    {
        return $this->hasMany(District::class, 'foreign_key');
    }
}
