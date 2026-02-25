<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\GenericUser;
use App\Models\VisitorSession;

class VisitorSessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Session-Token');
        if (!$token) {
            return response()->json(['message' => 'Missing X-Session-Token'], 401);
        }

        $session = VisitorSession::where('session_token', $token)->latest('id')->first();
        if (!$session) {
            return response()->json(['message' => 'Invalid session token'], 401);
        }

        // attach for controllers
        $request->attributes->set('visitor_session', $session);
        $request->attributes->set('visitor_id', (int) $session->visitor_id);

        // user resolver for Broadcast::auth & any auth checks
        $request->setUserResolver(function () use ($session) {
            return new GenericUser([
                'id' => (int) $session->visitor_id,
                'type' => 'visitor',
                'session_id' => (int) $session->id,
            ]);
        });

        return $next($request);
    }
}












// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Auth\GenericUser;
// use Illuminate\Support\Facades\Auth;
// use App\Models\VisitorSession;

// class VisitorSessionAuth
// {
//     public function handle(Request $request, Closure $next)
//     {
//         $token = $request->header('X-Session-Token');

//         if (!$token) {
//             return response()->json(['message' => 'Missing X-Session-Token'], 401);
//         }

//         $session = VisitorSession::query()
//             ->where('session_token', $token)
//             ->latest('id')
//             ->first();

//         if (!$session) {
//             return response()->json(['message' => 'Invalid session token'], 401);
//         }

//         $user = new GenericUser([
//             'id' => $session->visitor_id,
//             'type' => 'visitor',
//             'session_id' => $session->id,
//         ]);

//         // برای $request->user()
//         $request->setUserResolver(fn () => $user);

//         // برای Broadcast::auth (Guard-based)
//         Auth::setUser($user);

//         return $next($request);
//     }
// }