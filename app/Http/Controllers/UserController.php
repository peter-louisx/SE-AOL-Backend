<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    # Customer
    public function userRegister(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:20',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'profile' => 'nullable|url',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,
            'profile' => $request->profile,
        ]);

        return response()->json([
            'massage' => 'Masuk nih ke UserController',
            'user' => $user,
        ]);
    }

    # Seller
    public function sellerRegister()
    {
        return response()->json(['message' => 'Hello from UserController']);
    }

    # Vendor
    public function vendorRegister()
    {
        return response()->json(['message' => 'Hello from UserController']);
    }
}
