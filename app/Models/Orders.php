<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_addresses_id',
        'note',
        'delivery_type',
        'delivery_cost',
        'total_price',
        'order_type',
        'start_date',
        'end_date',
        'cancele_at',
        'paid_status',
        'snap_token',
        'invoice_number',
        'has_review'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function customerAddresses()
    {
        return $this->belongsTo(CustomerAddresses::class, 'customer_addresses_id');
    }

    public function orderDetails(){

        return $this->hasMany(OrderDetails::class, 'orders_id');
    }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'foreign_key');
    // }

    public function detailJsonView(){

    }

    public function address(){
        return $this->hasOne(CustomerAddresses::class, 'customer_addresses_id');
    }


}
