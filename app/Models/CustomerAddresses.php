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
        'district_id',
        'zipcode',
        'latitude',
        'longtitude'

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'foreign_key');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'foreign_key');
    }
}
