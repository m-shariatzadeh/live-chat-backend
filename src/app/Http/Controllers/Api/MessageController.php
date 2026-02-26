<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\MessageService;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Http\Request;
use App\Events\MessageDelete;
use App\Events\MessageUpdate;

class MessageController extends Controller
{
    protected MessageService $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

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


    public function store(StoreMessageRequest $request, Conversation $conversation)
    {
      $visitorId = (int) $request->attributes->get('visitor_id');

        $message = $this->service->sendVisitorMessage(
            $conversation,
            $visitorId,
            $request->body
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
