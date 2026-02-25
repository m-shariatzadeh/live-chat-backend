<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Services\ConversationService;
use App\Http\Requests\StoreConversationRequest;
// use Illuminate\Http\Request;

class ConversationController extends Controller
{
    protected ConversationService $service;

    public function __construct(ConversationService $service)
    {
        $this->service = $service;
    }

    public function store(StoreConversationRequest $request)
    {
        $session = $request->attributes->get('visitor_session');
$visitorId = (int) $request->attributes->get('visitor_id');

$visitor = \App\Models\Visitor::findOrFail($visitorId);

$conversation = $this->service->createConversation(
    $visitor,
    $session,
    $request->subject ?? 'Support Request'
);

        return response()->json($conversation);
    }


    public function show(Conversation $conversation)
    {
        return response()->json(
            $conversation->load([
                'messages' => fn($q) => $q->latest('id')->limit(20)
            ])
        );
    }
}
