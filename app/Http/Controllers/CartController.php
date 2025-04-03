<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        return response()->json(Cart::with(['customer', 'product', 'order'])
            ->where('customer_id', Auth::user()->id)
            ->get());
    }

    public function show($id)
    {
        $cart = Cart::with(['customer', 'product', 'order'])
            ->where('customer_id', Auth::user()->id)
            ->find($id);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = Cart::create([
            'customer_id' => Auth::user()->id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'message' => 'Cart created successfully!',
            'id' => $cart->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('customer_id', Auth::user()->id)
                    ->find($id);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $request->validate([
            'product_id' => 'sometimes|exists:products,id',
            'order_id' => 'sometimes|exists:orders,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $cart->update($request->only([
            'product_id', 'order_id', 'quantity'
        ]));

        return response()->json([
            'message' => 'Cart updated successfully!',
            'cart' => $cart,
        ]);
    }

    public function destroy($id)
    {
        $cart = Cart::where('customer_id', Auth::user()->id)
                    ->find($id);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart->delete();

        return response()->json(['message' => 'Cart deleted successfully!']);
    }
}
