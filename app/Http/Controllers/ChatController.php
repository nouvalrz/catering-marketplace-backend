<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Chats;
use App\Models\Customer;
use App\Models\RoomChat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kutia\Larafirebase\Facades\Larafirebase;

class ChatController extends Controller
{
    //

    public function indexForCustomer(){
        $customer = Customer::where('user_id', auth()->user()->id)->first();

        $roomChat = RoomChat::with(['latestChat', 'catering:id,name,image'])->where('customer_id', $customer->id)->get()->sortBy('latestChat.created_at', SORT_REGULAR, true)->values()->all();;


        return response()->json(["chats" => $roomChat]);

    }

    public function showForCustomer(Request $request){
        $customer = Customer::where('user_id', auth()->user()->id)->first();

        request()->validate([
           'catering_id' => 'required'
        ]);

        $roomChat = RoomChat::where([['customer_id', '=', $customer->id], ['catering_id', '=', request('catering_id')]])->first();

        if($roomChat == null){
            return response()->json(["chats" => []]);
        }

//        $chats = Chats::where([['sender_id', '=', $currentUser->id],['recipient_id', '=', $respondenUser->id]])->orWhere([['sender_id', '=', $respondenUser->id],['recipient_id', '=', $currentUser->id]])->orderBy('created_at', 'asc')->get();

        $chats = Chats::where('roomchats_id', $roomChat->id)->get();

        return response()->json(["chats" => $chats]);

    }

    public function sendForCustomer(Request $request){
        $customer = Customer::where('user_id', auth()->user()->id)->first();

        request()->validate([
            'catering_id' => 'required',
            'message' => 'required'
        ]);

        $roomChat = RoomChat::where([['customer_id', '=', $customer->id], ['catering_id', '=', request('catering_id')]])->first();

        if($roomChat == null){
            $roomChat = RoomChat::create(['customer_id' => $customer->id, 'catering_id' => request('catering_id')]);
        }

        $chat = Chats::create([
            'roomchats_id' => $roomChat->id,
            'sender' => "customer",
            'message' => request('message')
        ]);

        $roomChat->updated_at = Carbon::now();
        $roomChat->save();

//        Larafirebase::withTitle("Pesan Baru dari {$senderName}")->withBody($chat->message)->withAdditionalData( array_merge(["type" => "chat"], $chat->toArray()))->sendNotification($recipientUser->fcm_token);

        return response()->json(["chat" => $chat]);
    }
}
