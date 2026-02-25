<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class RequireVisitorPhone
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user(); // GenericUser from visitor.session
        if (!$user || ($user->type ?? null) !== 'visitor') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $visitor = Visitor::find($user->id);
        if (!$visitor || !$visitor->phone) {
            return response()->json([
                'message' => 'Phone required',
                'code' => 'PHONE_REQUIRED',
            ], 403);
        }

        return $next($request);
    }
}