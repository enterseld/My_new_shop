<?php

namespace App\Events;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class NewChatMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->chat_session_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'text' => $this->message->message_text,
            'sender_type' => $this->message->sender_type,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
