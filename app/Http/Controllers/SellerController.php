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

    public function editSellerProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->seller) {
            return response()->json(['error' => 'Pengguna belum login'], 401);
        }

        $request->validate([
            'store_name'     => 'required|string|max:30',
            'phone_number'   => 'required|regex:/^[0-9]+$/|max:15',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'address'        => 'required|string',
            'bank_account'   => 'required|regex:/^[0-9]+$/|max:20',
            'profile'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bank_name'      => 'nullable|string|max:50',
        ]);

        $user->update([
            'phone_number' => $request->phone_number,
            'email'        => $request->email,
        ]);

        $user->seller->update([
            'address'      => $request->address,
            'bank_account' => $request->bank_account,
            'bank_name'    => $request->bank_name,
        ]);

        $user->seller->brand->update([
            'store_name' => $request->store_name,
        ]);

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/profile_pictures', $fileName);

            $user->update(['profile' => str_replace('public/', '', $path)]);
        }

        return response()->json([
            'message'      => 'Seller Profile Updated!',
            'account'      => [
                'name'         => $user->seller->brand->store_name ?? '',
                'email'        => $user->email,
                'phone'        => $user->phone_number,
                'address'      => $user->seller->address ?? '',
                'bank_account' => $user->seller->bank_account ?? '',
                'bank_name'    => $user->seller->bank_name ?? '',
                'image'        => $user->profile ? asset('storage/' . $user->profile) : null,
            ],
        ]);
    }
}
