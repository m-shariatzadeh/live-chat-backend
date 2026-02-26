<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class MessageService
{
    public function sendVisitorMessage(
        Conversation $conversation,
        int $visitorId,
        string $body,
        int|null $reply_to
    ): Message {

        $message = DB::transaction(function () use ($conversation, $visitorId, $body, $reply_to) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_type'     => 'visitor',
                'sender_id'       => $visitorId,
                'type'            => 'text',
                'body'            => $body,
                'reply_to'        => $reply_to,
            ]);

            $this->updateConversationMeta($conversation, $message);
            return $message;
        });

        broadcast(new MessageSent($message))->toOthers();
        return $message;
    }

    public function sendAgentMessage(
        Conversation $conversation,
        int $agentId,
        string $body
    ): Message {

        $message = DB::transaction(function () use ($conversation, $agentId, $body) {

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_type'     => 'agent',
                'sender_id'       => $agentId,
                'type'            => 'text',
                'body'            => $body,
            ]);

            // اگر اولین پاسخ Agent باشد
            if (!$conversation->first_response_at) {
                $conversation->update([
                    'first_response_at' => now(),
                    'status' => 'active',
                    'first_admin_id' => $agentId,
                ]);
            }

            $this->updateConversationMeta($conversation, $message);
            return $message;

        });
        broadcast(new MessageSent($message))->toOthers();
        return $message;
    }

    private function updateConversationMeta(
        Conversation $conversation,
        Message $message
    ): void {

        $conversation->update([
            'last_message_id' => $message->id,
            'last_message_at' => $message->created_at,
        ]);
    }
}
