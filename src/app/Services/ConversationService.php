<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Visitor;
use App\Models\VisitorSession;
use Illuminate\Support\Facades\DB;

class ConversationService
{
    public function createConversation(
        Visitor $visitor,
        VisitorSession $session,
        ?string $subject = null
    ): Conversation {

        return DB::transaction(function () use ($visitor, $session, $subject) {

            return Conversation::create([
                'visitor_id'   => $visitor->id,
                'session_id'   => $session->id,
                'status'       => 'waiting',
                'priority'     => 'normal',
                'subject'      => $subject,
                'started_at'   => now(),
            ]);
        });
    }

    public function activateConversation(Conversation $conversation, int $agentId): void
    {
        if ($conversation->status === 'waiting') {
            $conversation->update([
                'status' => 'active',
                'first_admin_id' => $agentId,
            ]);
        }
    }

    public function resolveConversation(Conversation $conversation): void
    {
        $conversation->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function closeConversation(Conversation $conversation): void
    {
        $conversation->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }
}