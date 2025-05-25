<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Brand;
use App\Models\CustomerAddress;

class CustomerController extends Controller
{
    public function customerRegister(Request $request)
    {
        info('Hi2323');
        info($request->all());
        $request->validate([
            'name' => 'required|string|max:20',
            'phone_number' => 'required|regex:/^[0-9]+$/|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', //send another variable "password_confirmation" being exact as pw
        ]);
        info($request->all());
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
        ], 201);
    }

    public function addCustomerAddress(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login'], 401);
        }

        $request->validate([
            'address' => 'required|string',
            'label' => 'required|string|max:10', # opt2 = 'label' => 'required|string|in:Home,Office,Apartment,Other',
        ]);

        $customer = $user->customer;
        $customer->address()->create([
            'label' => $request->label,
            'address' => $request->address,
        ]);

        return response()->json(['message' => "Alamat telah ditambahkan"]);
    }

    public function getCustomerAddresses() {
        $user = auth()->user();
        $addresses = CustomerAddress::where('customer_id', $user->id)
            ->orderBy('id', 'asc')
            ->get();
        return response()->json($addresses);
    }

    public function editCustomerAddress(Request $request, $addressId){
        $user = Auth::user();

        if(!$user || !$user->customer){
            return response()->json(['error' => 'Pengguna belum login'], 401);
        }

        $request->validate([
            'address' => 'required|string',
            'label' => 'required|string|max:10', # opt2 = 'label' => 'required|string|in:Home,Office,Apartment,Other',
        ]);

        $addresses = CustomerAddress::where('id', $addressId)
            ->where('customer_id', $user->customer->id)
            ->first();
        
        if (!$addresses) {
            return response()->json(['error' => 'Alamat tidak ditemukan'], 404);
        }

            $addresses->update([
                'label' => $request->label,
                'address' => $request->address,
            ]);

            return response()->json(['message' => "Alamat telah diedit"]);
        }

    public function deleteCustomerAddress($id){
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login'], 401);
        }

        $addresses = CustomerAddress::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$addresses) {
            return response()->json(['error' => 'Alamat tidak ditemukan'], 404);
        }

        $addresses->delete();

        return response()->json(['message' => "Alamat telah dihapus"]);
    }

    public function customerProfilePicture(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login'], 401);
        }

        $request->validate([
            'profile' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $file = $request->file('profile');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('public/profile_pictures', $fileName);

        $user->update(['profile' => str_replace('public/', '', $path)]);

        return response()->json([
            'message' => 'Profile udah diupdate',
            'profile_url' => asset('storage/' . $user->profile),
        ]);
    }

    public function editCustomerProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:20',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|regex:/^[0-9]+$/|max:15',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json([
            'message' => 'Customer Profile Picture udah keupdate',
            'user' => $user,
        ]);
    }
}
