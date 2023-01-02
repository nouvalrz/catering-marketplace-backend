<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'recipient_name',
        'address',
        'village_id',
        'latitude',
        'longitude',
        'phone'

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'foreign_key');
    }
    public function village()
    {
        return $this->belongsTo(Village::class, 'foreign_key');
    }
}
