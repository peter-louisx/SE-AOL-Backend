<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $res = Brand::with(['sellers.user', 'products'])->get();
        $res = $res->map(function ($brand) {
            return [
                'id' => $brand->id,
                'name' => $brand->name,
                'logo' => $brand->logo,
            ];
        });
        return response()->json($res);
    }

    public function show($id)
    {
        $brand = Brand::with(['products'])->find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
        return response()->json($brand);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name|max:50',
            'description' => 'nullable|string',
        ]);

        $brand = Brand::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Brand created successfully!',
            'id' => $brand->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|unique:brands,name|max:50',
            'description' => 'nullable|string',
        ]);

        $brand->update($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Brand updated successfully!',
            'brand' => $brand,
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $brand->delete();

        return response()->json(['message' => 'Brand deleted successfully!']);
    }
}
