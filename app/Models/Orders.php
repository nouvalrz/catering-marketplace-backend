<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $guarded = [];

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

    public function subsOrderDetails(){

        return $this->hasMany(OrderDetails::class, 'orders_id')->get()->groupBy('delivery_datetime')->all();
    }


}
