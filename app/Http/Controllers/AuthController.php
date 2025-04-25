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

        $user = User::where('email', $request->email)->first();

        if (!$user || !password_verify($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {   
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'massage' => 'logout successfull',
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->load('customer');

            return response()->json([
                'full_name' => $user->name,
                'green_point' => $user->customer ? $user->customer->green_point : 0,
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}