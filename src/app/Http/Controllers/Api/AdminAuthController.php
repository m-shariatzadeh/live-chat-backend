<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        /** @var Agent|null $agent */
        $agent = Agent::where('email', $request->email)->first();

        if (!$agent || !$agent->is_active || !Hash::check($request->password, $agent->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        $token = $agent->createToken('admin-panel')->plainTextToken;

        return response()->json([
            'token' => $token,
            'agent' => [
                'id' => $agent->id,
                'email' => $agent->email,
                'name' => $agent->name,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user(); // sanctum
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['ok' => true]);
    }
}