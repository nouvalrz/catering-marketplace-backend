<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCoverages extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function catering()
    {
        return $this->belongsTo(Catering::class, 'foreign_key');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'foreign_key');
    }
}
