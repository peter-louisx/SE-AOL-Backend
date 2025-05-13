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

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->load(['customer', 'seller']);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone_number,
            'email' => $user->email,
            'profile' => $user->profile,

            'customer' => $user->customer ? [
                'green_point' => $user->customer->green_point,
            ] : null,

            'seller' => $user->seller ? [
                'balance' => $user->seller->balance,
                'bank_account' => $user->seller->bank_account,
                'address' => $user->seller->address ?? null,
                'bank_name' => $user->seller->bank_name,
            ] : null,
        ]);
    }
}