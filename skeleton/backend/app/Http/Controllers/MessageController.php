<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Message;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use LRedis;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::User();
        $userId = $user->id;

        $messages = Message::getMessages($userId, $request->userId);

        return $this->toJsonResponse(200, $messages, false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::User();
        $userId = $user->id;

        $message = new Message;
        $message->sender_id = $userId;
        $message->recipient_id = $request->userId;
        $message->body = $request->body;
        $message->created_at = new \DateTime();
        $message->save();

        if (isset($message->id)) {

            if ($message->sender_id > $message->recipient_id) {
                $chatId = $message->sender_id . '_' . $message->recipient_id;
            } else {
                $chatId = $message->recipient_id . '_' . $message->sender_id;
            }

            $message = Message::getMessage($message->id);
            $message->chat_id = $chatId;
            $redis = LRedis::connection();
            $redis->publish('message-channel', json_encode($message));

            return $this->toJsonResponse(201, $message, false);
        } else {
            $error = 'Nothing was added';

            return $this->toJsonResponse(404, false, $error);
        }
    }
}
