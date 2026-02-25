<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\VisitorSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisitorSessionController extends Controller
{
    public function store(Request $request)
    {
        $visitor = Visitor::create([
            'name' => $request->input('name', 'Guest'),
        ]);

        $session = VisitorSession::create([
            'uuid' => Str::uuid(),
            'visitor_id' => $visitor->id,
            'session_token' => Str::random(40),
            'started_at' => now(),
        ]);

        return response()->json([
            'visitor_id' => $visitor->id,
            'session_id' => $session->id,
            'session_token' => $session->session_token,
        ]);
    }
}