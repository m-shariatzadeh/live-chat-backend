<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageDelete implements ShouldBroadcast
{
    use SerializesModels;

    public function __construct(public int $message_id, public int $conversation_id) {}

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->conversation_id);
    }

    public function broadcastAs()
    {
        return 'message.delete';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message_id,
        ];
    }
}
