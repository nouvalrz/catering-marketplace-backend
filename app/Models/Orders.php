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
        'note',
        'delivery_type',
        'delivery_cost',
        'total_price',
        'order_type',
        'start_date',
        'end_date',
        'canceled_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'foreign_key');
    }
    public function customerAddresses()
    {
        return $this->belongsTo(CustomerAddresses::class, 'foreign_key');
    }
    public function catering()
    {
        return $this->belongsTo(Catering::class, 'foreign_key');
    }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'foreign_key');
    // }
}
