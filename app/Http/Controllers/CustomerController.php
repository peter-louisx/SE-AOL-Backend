<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Brand;

class CustomerController extends Controller
{
    public function customerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'phone_number' => 'required|regex:/^[0-9]+$/|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,
            'profile' => null,
        ]);

        $user->customer()->create([
            'green_point' => 0,
            'voucher' => null,
        ]);

        return response()->json([
            'massage' => 'Masuk nih ke customer',
            'user' => $user,
        ]);
    }

    public function addCustomerAddress(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Pengguna belum login'], 401);
        }

        $customer = $user->customer;
        $customer->address()->create([
            'address' => $request->address,
        ]);

        return response()->json(['message' => "Alamat telah ditambahkan"]);
    }
}
