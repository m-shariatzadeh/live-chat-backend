<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageDelete;
use App\Events\MessageUpdate;
use App\Services\MessageService;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index(Request $request, Conversation $conversation)
    {
        $lastId = $request->query('last_id');

        $query = $conversation->messages()
            ->orderByDesc('id')
            ->limit(20);

        if ($lastId) {
            $query->where('id', '<', $lastId);
        }

        $messages = $query->get()->reverse()->values();

        return response()->json($messages);
    }

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

    public function update(Request $request, Message $message)
    {
        $request->validate(['body' => 'required']);

        $message->update([
            'body' => $request->body
        ]);

        broadcast(new MessageUpdate($message))->toOthers();

        return response()->json($message);
    }

    public function delete(Message $message)
    {
        $message_id = $message->id;
        broadcast(new MessageDelete($message_id, $message->conversation_id))->toOthers();

        $message->delete();

        return response()->json($message_id);
    }
}
