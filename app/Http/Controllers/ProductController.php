<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with(['brand', 'category', 'tag'])->get());
    }

    public function show($id)
    {
        $product = Product::with(['brand', 'category', 'tag'])->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'price' => 'required|integer',
            'sold' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5',
            'tag_id' => 'required|exists:product_tags,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'sold' => $request->sold,
            'stock' => $request->stock,
            'description' => $request->description,
            'rating' => $request->rating,
            'tag_id' => $request->tag_id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        return response()->json([
            'message' => 'Product created successfully!',
            'id' => $product->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:50',
            'price' => 'sometimes|integer',
            'sold' => 'sometimes|integer',
            'stock' => 'sometimes|integer',
            'description' => 'sometimes|string',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'tag_id' => 'sometimes|exists:product_tags,id',
            'category_id' => 'sometimes|exists:categories,id',
            'brand_id' => 'sometimes|exists:brands,id',
        ]);

        $product->update($request->only([
            'name', 'price', 'sold', 'stock', 'description', 'rating', 'tag_id', 'category_id', 'brand_id'
        ]));

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully!']);
    }
}
