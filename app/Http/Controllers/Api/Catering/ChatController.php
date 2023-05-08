<?php

namespace App\Http\Controllers\Api\Catering;

use App\Events\Message;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Catering;
use App\Models\Chats;
use App\Models\Customer;
use App\Models\RoomChats;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function roomChats()
    {
        //get products
        // $products = Product::with('category')->when(request()->q,
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = Catering::where('user_id', $userId)->value('id');
        // $roomChats = RoomChats::where('catering_id', $cateringId)->orderBy('updated_at', 'desc')->get();

        // $roomChats = RoomChats::with('latestChat')->with('imageCustomer')->with('imageCatering')->where('catering_id', $cateringId)->whereHas('imageCustomer',
        // function($roomChats) {
        //     $roomChats = $roomChats->where('name', 'like', '%'. request()->q . '%');
        // })->get()->sortByDesc(function($roomChats){
        //     $roomChats = $roomChats->latest_chat->created_at;
        //  })
        //  ->all();

        $roomChats = RoomChats::with('latestChat')->with('imageCustomer')->with('imageCatering')->where('catering_id', $cateringId)->whereHas('imageCustomer',
        function($roomChats) {
            $roomChats = $roomChats->where('name', 'like', '%'. request()->q . '%');
        })->get()->sortBy('latestChat.created_at', SORT_REGULAR, true)->values()->all();

        // dd($roomChats);

        if($roomChats){
            return new ChatResource(true, 'List Data Chats', $roomChats, null, null);
        }

        //return with Api Resource
        return new ChatResource(false, 'List Data Chats Kosong', null, null, null);
    }

    public function chats($id){
        $chats = Chats::with('room')->where('roomchats_id', $id)->orderBy('created_at')->get();
        $room = RoomChats::whereId($id)->first();
        $profileCustomer = Customer::whereId($room->customer_id)->first(['id','name','image']);
        $profileCatering = Catering::whereId($room->catering_id)->first(['id','name','image']);
        // $chats->link = asset('storage/caterings/');
        
        // $roomChats = RoomChats::with('imageCustomer')->where('id', $id)->first();
        // $chats->image = $roomChats;
        // return 'asdas';
        return new ChatResource(true, 'List Data Chats', $chats, $profileCustomer, $profileCatering);
        // return new ChatResource(true, 'List Data Chats', $customer);

    }

    public function createMessage(Request $request){
        $roomChats = RoomChats::create([
            'customer_id' => $request->customer_id,
            'catering_id' => $request->catering_id,
        ]);

        $chats = Chats::create([
            'roomchats_id' => $roomChats->id,
            'message' => $request->message,
            'sender' => $request->sender,
        ]);

        if($chats){   
            return new ChatResource(true, 'Data chat Berhasil Disimpan!', null, null, null);
        }
        return new ChatResource(false, 'Data chat gagal Disimpan!', null, null, null);

    }

    public function sendMessage(Request $request){

        $chats = Chats::create([
            'roomchats_id' => $request->roomchats_id,
            'message' => $request->message,
            'sender' => $request->sender,
        ]);

        if($chats){   
            return new ChatResource(true, 'Data chat Berhasil Disimpan!', null, null, null);
        }
        return new ChatResource(false, 'Data chat gagal Disimpan!', null, null, null);

    }

    public function message(Request $request)
    {
        event(new Message($request->input('username'), $request->input('message')));
        
        return[];
    }
}
