<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return response()->json(Order::with(['customer', 'product'])
            ->where('customer_id', $user->id) 
            ->get());
    }

    public function show($id)
    {
        $user = Auth::user();
        $order = Order::with(['customer', 'product'])
            ->where('customer_id', $user->id)
            ->find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_status' => 'required|string|max:10',
            'total_pay' => 'required|integer',
            'destination' => 'required|string',
            'order_date' => 'required|date',
            'delivery_type' => 'required|string|size:7',
        ]);

        $order = Order::create([
            'customer_id' => $user->id,
            'product_id' => $request->product_id,
            'order_status' => $request->order_status,
            'total_pay' => $request->total_pay,
            'destination' => $request->destination,
            'order_date' => $request->order_date,
            'delivery_type' => $request->delivery_type,
        ]);

        return response()->json([
            'message' => 'Order created successfully!',
            'id' => $order->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::where('customer_id', $user->id)->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $request->validate([
            'product_id' => 'sometimes|exists:products,id',
            'order_status' => 'sometimes|string|max:10',
            'total_pay' => 'sometimes|integer',
            'destination' => 'sometimes|string',
            'order_date' => 'sometimes|date',
            'delivery_type' => 'sometimes|string|size:7',
        ]);

        $order->update($request->only([
            'product_id', 'order_status', 'total_pay', 'destination',
            'order_date', 'delivery_type'
        ]));

        return response()->json([
            'message' => 'Order updated successfully!',
            'order' => $order,
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user(); 
        $order = Order::where('customer_id', $user->id)->find($id); 

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully!']);
    }
}
