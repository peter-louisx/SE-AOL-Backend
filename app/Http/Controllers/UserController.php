<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brand;

class UserController extends Controller
{
    # Customer
    public function customerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'phone_number' => 'required|regex:/^[0-9]+$/|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'profile' => 'nullable|url',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,
            'profile' => $request->profile,
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

    # Seller
    public function sellerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'store_name' => 'required|string|max:30',
            'phone_number' => 'required|regex:/^[0-9]+$/|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'profile' => 'nullable|url',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,
            'profile' => $request->profile,
        ]);
        
        $brand = Brand::create([
            'name' => $request->store_name,
            'rating' => 0.0,
            'description' => 'No Description',
        ]);

        $seller = $user->seller()->create([
            'address' => 'No Address',
            'balance' => 0,
            'brand_id' => $brand->id,
        ]);

        return response()->json([
            'message' => 'Udah masuk seller',
            'user' => $user,
        ]);
    }

    public function addCustomerAddress(Request $request){
        $customer = Auth::user();

        $customer->address()->create([
            'address' => $request->address,
        ]);
        
        return response()->json(['message' => "alamat telah ditambahkan"]);
    }
}
