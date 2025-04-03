<?php

namespace App\Http\Controllers;

use App\Models\ProductTag;
use Illuminate\Http\Request;

class ProductTagController extends Controller
{
    public function index()
    {
        return response()->json(ProductTag::all());
    }

    public function show($id)
    {
        $productTag = ProductTag::find($id);
        if (!$productTag) {
            return response()->json(['message' => 'Product Tag not found'], 404);
        }
        return response()->json($productTag);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:product_tags,name|max:20',
        ]);

        $productTag = ProductTag::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Product Tag created successfully!',
            'id' => $productTag->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $productTag = ProductTag::find($id);
        if (!$productTag) {
            return response()->json(['message' => 'Product Tag not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|unique:product_tags,name|max:20',
        ]);

        $productTag->update($request->only(['name']));

        return response()->json([
            'message' => 'Product Tag updated successfully!',
            'product_tag' => $productTag,
        ]);
    }

    public function destroy($id)
    {
        $productTag = ProductTag::find($id);
        if (!$productTag) {
            return response()->json(['message' => 'Product Tag not found'], 404);
        }

        $productTag->delete();

        return response()->json(['message' => 'Product Tag deleted successfully!']);
    }
}
