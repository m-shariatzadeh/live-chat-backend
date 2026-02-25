<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;
use App\Models\Agent;

Broadcast::channel('conversation.{conversationId}', function ($user, int $conversationId) {

    // Admin/Agent (Sanctum)
    if ($user instanceof Agent) {
        return true;
    }

    // Visitor (GenericUser از middleware)
    if (is_object($user) && ($user->type ?? null) === 'visitor') {
        return Conversation::query()
            ->where('id', $conversationId)
            ->where('visitor_id', $user->id)
            ->exists();
    }

    return false;
});