<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\NewChatMessage;

class ChatController extends Controller
{
    // Створити нову сесію (якщо ще немає)
    public function startSession(Request $request)
    {
        if (Auth::check()) {
            $session = ChatSession::firstOrCreate([
                'user_id' => Auth::id(),
            ]);
        } else {
            $guestToken = $request->cookie('guest_token') ?? Str::uuid();
            $session = ChatSession::firstOrCreate([
                'guest_token' => $guestToken,
            ]);
            // Встановити куку для гостя
            if (!$request->cookie('guest_token')) {
                cookie()->queue(cookie('guest_token', $guestToken, 60 * 24 * 30)); // на 30 днів
            }
        }

        return response()->json($session);
    }

    // Надіслати повідомлення
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_session_id' => 'required|exists:chat_sessions,id',
            'message_text' => 'required|string',
        ]);

        $message = Message::create([
            'chat_session_id' => $request->chat_session_id,
            'sender_type' => Auth::check() ? 'user' : 'guest',
            'sender_id' => Auth::id(),
            'message_text' => $request->message_text,
        ]);

        event(new NewChatMessage($message));

        return response()->json($message);
    }

    // Отримати всі повідомлення певної сесії
    public function getMessages(Request $request, $sessionId)
    {
        $messages = Message::where('chat_session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }
}
