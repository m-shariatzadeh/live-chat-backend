<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\MessageService;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function store(Request $request, Conversation $conversation, MessageService $service)
    {
        $data = $request->validate([
            'admin_id' => ['required', 'integer'],
            'body' => ['required', 'string', 'max:4000'],
        ]);

        $message = $service->sendAgentMessage(
            $conversation,
            $data['admin_id'],
            $data['body']
        );

        return response()->json($message);
    }
}