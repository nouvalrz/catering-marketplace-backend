<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Chats;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Kutia\Larafirebase\Facades\Larafirebase;

class ChatController extends Controller
{
    //

    public function indexForCustomer(){
        $user = auth()->user();

        $chats = Chats::where('sender_id', $user->id)->orWhere('recipient_id', $user->id)->groupBy('sender_id', 'recipient_id')->orderBy('created_at', 'desc')->get();

//        return response()->json($chats);

        $cateringUserIds = [];

        $chatGroup = [];

        foreach ($chats as $chat){
            $cateringUserId = $chat->sender_id == $user->id ? $chat->recipient_id : $chat->sender_id;
            $cateringUserIds[] = $cateringUserId;
        }
        $removeDuplicate = array_unique($cateringUserIds);
        $cateringUserIdsComplete = [];
        foreach ($removeDuplicate as $value){
            $cateringUserIdsComplete[] = $value;
        }

        foreach ($cateringUserIdsComplete as $value){
            $lastChat = Chats::where('sender_id', $value)->orWhere('recipient_id', $value)->orderBy('created_at', 'desc')->first();
            $catering = Catering::where('user_id', $value)->first();

            $newChatGroup = [
                "catering_name" => $catering->name,
                "catering_image" => $catering->original_path,
                "catering_id" => $catering->id,
                "last_chat" => $lastChat
            ];
            $chatGroup[] = $newChatGroup;
        }


        return response()->json(["chats" => $chatGroup]);

    }

    public function show(Request $request){
        $currentUser = auth()->user();

        request()->validate([
           'recipient_id' => 'required'
        ]);

        $respondenUser = User::find(request('recipient_id'));

        $chats = Chats::where([['sender_id', '=', $currentUser->id],['recipient_id', '=', $respondenUser->id]])->orWhere([['sender_id', '=', $respondenUser->id],['recipient_id', '=', $currentUser->id]])->orderBy('created_at', 'asc')->get();

        return response()->json(["chats" => $chats]);

    }

    public function send(Request $request){
        $senderUser = auth()->user();
        $senderName = null;

        request()->validate([
            'recipient_id' => 'required',
            'recipient_type' => 'required',
            'message' => 'required'
        ]);

        $recipientUser = null;
        $recipientName = null;

        if(request('recipient_type') == "customer"){
            $customer = Customer::find(request('recipient_id'));
            $recipientUser = User::find($customer->user_id);
            $recipientName = $recipientUser->name;
            $senderName = Catering::where('user_id', $senderUser->id)->first()->name;
        }else if(request('recipient_type') == "catering"){
            $catering = Catering::find(request('recipient_id'));
            $recipientUser = User::find($catering->user_id);
            $recipientName = $catering->name;
            $senderName = $senderUser->name;
        }

        $chat = Chats::create([
            'sender_id' => $senderUser->id,
            'recipient_id' => $recipientUser->id,
            'message' => request('message')
        ]);

        Larafirebase::withTitle("Pesan Baru dari {$senderName}")->withBody($chat->message)->withAdditionalData( array_merge(["type" => "chat"], $chat->toArray()))->sendNotification($recipientUser->fcm_token);

        return response()->json(["chat" => $chat]);
    }
}
