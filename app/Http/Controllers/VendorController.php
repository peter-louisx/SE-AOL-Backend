<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        return response()->json(Vendor::all());
    }

    public function show($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }
        return response()->json($vendor);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:15',
        ]);

        $vendor = Vendor::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json([
            'message' => 'Vendor created successfully!',
            'id' => $vendor->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string|max:15',
        ]);

        $vendor->update($request->only(['name', 'address', 'phone_number']));

        return response()->json([
            'message' => 'Vendor updated successfully!',
            'vendor' => $vendor,
        ]);
    }

    public function destroy($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $vendor->delete();

        return response()->json(['message' => 'Vendor deleted successfully!']);
    }
}
