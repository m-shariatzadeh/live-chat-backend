<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Http\Requests\AssignConversationRequest;

use Illuminate\Http\Request;

class AdminConversationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Conversation::query()
                ->when(
                    $request->status,
                    fn($q) =>
                    $q->where('status', $request->status)
                )
                ->latest()
                ->paginate(20)
        );
    }



    public function assign(AssignConversationRequest $request, Conversation $conversation)
    {
        $conversation->update([
            'first_admin_id' => $request->admin_id,
            'status' => 'active',
        ]);

        return response()->json($conversation);
    }    // public function assign(Request $request, Conversation $conversation)
    // {
    //     $conversation->update([
    //         'first_admin_id' => $request->admin_id,
    //         'status' => 'active',
    //     ]);

    //     return response()->json($conversation);
    // }

    public function resolve(Conversation $conversation)
    {
        $conversation->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return response()->json($conversation);
    }

    public function close(Conversation $conversation)
    {
        $conversation->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return response()->json($conversation);
    }
}
