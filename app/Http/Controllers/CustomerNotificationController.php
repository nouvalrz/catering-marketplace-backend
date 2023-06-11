<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerNotificationController extends Controller
{
    //

    public function index(){
        $user = auth()->user();
        $notifications = Customer::where('user_id', $user->id)->first()->notifications()->get();

        return response()->json(["notifications" => $notifications]);
    }
}
