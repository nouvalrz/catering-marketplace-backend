<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddresses;
use App\Models\Village;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    //
    public function getAllAddresses(Request $request){
        $user = auth()->user();
        $addresses = CustomerAddresses::where('customer_id', $user->id)->get();

        return response()->json([
            "addresses" => $addresses
        ]);
    }

    public function addAddress(Request $request){
        $user = auth()->user();
        request()->validate([
            'recipient_name' => 'required',
            'address' => 'required',
            'village_name' => 'required',
            'latitude'=> 'required',
            'longitude'=> 'required',
            'phone' => 'required'
        ]);


        $address = CustomerAddresses::create([
            'customer_id' => $user->id,
            'recipient_name' => request('recipient_name'),
            'address' => request('address'),
            'latitude' => request('latitude'),
            'longitude' => request('longitude'),
            'phone' => request('phone')
        ]);

        return response()->json([
            "address" => $address
        ]);
    }
}
