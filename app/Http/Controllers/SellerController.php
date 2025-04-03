<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Brand;

class SellerController extends Controller
{
    public function sellerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'store_name' => 'required|string|max:30',
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
        
        $brand = Brand::create([
            'name' => $request->store_name,
            'description' => 'No Description',
        ]);

        $seller = $user->seller()->create([
            'address' => 'No Address',
            'balance' => 0,
            'brand_id' => $brand->id,
            'bank_account' => null,
        ]); 

        return response()->json([
            'message' => 'Udah masuk seller',
            'user' => $user,
        ]);
    }

    public function addBankAccount(Request $request)
    {
        $seller = Auth::user();

        if (!$seller) {
            return response()->json(['error' => 'Pengguna belum login'], 401);
        }

        $request->validate([
            'bank_account' => 'required|regex:/^[0-9]+$/|max:20',
        ]);

        $seller->seller->update($request->only(['bank_account']));

        return response()->json([
            'message' => 'Bank account updated!',
            'Account' => $seller,
        ]);
    }
}
