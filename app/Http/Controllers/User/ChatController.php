<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function ask(Request $request){
        $messages = $request->input('messages');

        if (!is_array($messages)) {
            return response()->json(['error' => 'Invalid messages format'], 422);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'mistralai/mistral-small-3.2-24b-instruct:free',
            'messages' => $messages,
            'max_tokens' => 1024,
            'temperature' => 0.4, # creativity
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'OpenRouter API error'], 500);
        }

        return response()->json([
            'reply' => $response->json('choices.0.message.content'),
        ]);
    }
}
