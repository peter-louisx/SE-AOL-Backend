<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
        
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
        
            $token = Str::random(64);
            $user->tokens()->create([
                'name' => 'auth_token',
                'token' => hash('sha256', $token),
                'abilities' => ['*'],
                'expires_at' => now()->addDays(7),
            ]);
        
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,  // Mengirimkan token yang digunakan
            ])->cookie('auth_token', $token, 60 * 24 * 7, '/', null, true, true);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $token = $request->cookie('auth_token');

        if ($token) {
            PersonalAccessToken::where('token', hash('sha256', $token))->delete();

            return response()->json(['message' => 'Logout successful'])
                ->cookie('auth_token', '', -1);
        }

        return response()->json(['message' => 'No active session'], 400);
    }

    public function user(Request $request)
    {
        $token = $request->cookie('auth_token');

        if ($token) {
            $accessToken = PersonalAccessToken::where('token', hash('sha256', $token))->first();
            if ($accessToken && !now()->greaterThan($accessToken->expires_at)) {
                return response()->json(['user' => User::find($accessToken->tokenable_id)]);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}