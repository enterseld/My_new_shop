<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ChatMessages;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatSessionsController extends Controller
{
    public function initSession(Request $request)
    {

        if (Auth::check()) {
            $session = ChatSession::firstOrCreate([
                'user_id' => Auth::id(),
            ]);
            $chatMessages = $session->chatMessages;
            return response()->json([
                'session' => $session,
                'messages' => $chatMessages
            ]);
        } else {
            $guestToken = $request->cookie('guest_token');

            if (!$guestToken) {
                $guestToken = Str::uuid();
                cookie()->queue(cookie('guest_token', $guestToken, 60 * 24 * 30)); // 30 days
            }

            $session = ChatSession::firstOrCreate([
                'guest_token' => $guestToken,
            ]);
            $chatMessages = $session->chatMessages;
            return response()->json([
                'session' => $session,
                'messages' => $chatMessages
            ]);
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_session_id' => 'required|exists:chat_sessions,id',
            'message_text' => 'required|string'
        ]);

        $message = new ChatMessages([
            'chat_session_id' => $request->chat_session_id,
            'sender_type' => Auth::check() ? 'user' : 'guest',
            'sender_id' => Auth::id(),
            'message_text' => $request->message_text
        ]);

        $message->save();

        return response()->json([
            'id' => $message->id,
            'chat_session_id' => $message->chat_session_id,
            'sender_type' => $message->sender_type,
            'sender_id' => $message->sender_id,
            'message_text' => $message->message_text,
            'created_at' => $message->created_at,
            'updated_at' => $message->updated_at
        ]);
    }
}
