<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_address_id',
        'catering_id',
        'invoice_number',
        'note',
        'delivery_cost',
        'total_price',
        'order_type',
        'status',
        'start_date',
        'end_date',
        'canceled_at',
        'has_review',
        'payment_expiry',
        'snap_token',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function customerAddresses()
    {
        return $this->belongsTo(CustomerAddresses::class, 'customer_addresses_id');
    }
    public function catering()
    {
        return $this->belongsTo(Catering::class, 'catering_id');
    }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'foreign_key');
    // }
}
