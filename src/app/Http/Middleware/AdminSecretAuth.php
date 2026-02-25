<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Auth;

class AdminSecretAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Admin-Token');

        if (!$token) {
            return response()->json(['message' => 'Missing X-Admin-Token'], 401);
        }

        // نکته: تو خروجی‌ات expected آخرش newline داشت؛ پس trim خیلی مهمه
        $expected = trim((string) config('livechat.admin_token'));

        if (!hash_equals($expected, trim($token))) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $user = new GenericUser([
            'id' => 1,
            'type' => 'admin',
        ]);

        $request->setUserResolver(fn () => $user);
        Auth::setUser($user);

        return $next($request);
    }
}