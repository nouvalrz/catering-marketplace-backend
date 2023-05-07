<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function caterings(){
        return $this->hasMany(Catering::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }
}
