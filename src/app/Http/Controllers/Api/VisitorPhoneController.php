<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisitorSession;

class VisitorPhoneController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        // از middleware visitor.session میاد
        $user = $request->user(); // GenericUser
        if (!$user || ($user->type ?? null) !== 'visitor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $session = VisitorSession::find($user->session_id);
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 401);
        }

        $visitor = $session->visitor;
        $visitor->update([
            'phone' => $request->phone,
            'last_seen_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'visitor_id' => $visitor->id,
            'phone' => $visitor->phone,
        ]);
    }
}