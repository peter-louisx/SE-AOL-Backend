<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'User not logged in or not a customer'], 401);
        }

        $res = Cart::with(['customer', 'product', 'product.brand'])
            ->where('customer_id', $user->customer->id)
            ->get();

        $response = $res->map(function ($item) {
            return [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'description' => $item->product->description,
                'brand' => $item->product->brand->name,
                'price' => $item->product->price,
                'currency' => 'IDR', // Assuming USD, change as needed
                'quantity' => $item->quantity,
                'image' => $item->product->product_url, // Assuming product has an image attribute
            ];
        });

        return response()->json($response);
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'User not logged in or not a customer'], 401);
        }


        $cart = Cart::with(['customer', 'product'])
            ->where('customer_id', $user->customer->id)
            ->get();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'User not logged in or not a customer'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);


        $cart = Cart::create([
            'customer_id' => $user->customer->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'message' => 'Cart created successfully!',
            'id' => $cart->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'User not logged in or not a customer'], 401);
        }

        $cart = Cart::where('customer_id', $user->customer->id)
            ->find($id);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $request->validate([
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $cart->update($request->only([
            'product_id',
            'quantity'
        ]));

        return response()->json([
            'message' => 'Cart updated successfully!',
            'cart' => $cart,
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'User not logged in or not a customer'], 401);
        }

        $cart = Cart::where('customer_id', $user->customer->id)
            ->where('product_id', $id)
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart->delete();

        return response()->json(['message' => 'Cart deleted successfully!']);
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();


        if (!$user || !$user->customer) {
            return response()->json(['error' => 'User not logged in or not a customer'], 401);
        }

        $cartItems = Cart::where('customer_id', $user->customer->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $amount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $deliveryOptions = [
            'economy' => 3000,
            'regular'   => 5000,
            'instant' => 8000,
        ];

        $shippingFee = 1000;
        $serviceFee = 2000;
        $deliveryFee = $deliveryOptions[$request->input('delivery_option', 'economy')] ?? 3000;


        $params = [
            'external_id' => 'cart-' . $user->customer->id . '-' . time(),
            'description' => 'Checkout for customer ' . $user->customer->id,
            'amount' => $amount + $shippingFee + $serviceFee + $deliveryFee,
            'invoice_duration' => 172800,
            'currency' => 'IDR',
            'reminder_time' => 1,
            'customer' => [
                'given_names' => "Pelanggan " . $user->customer->id,
                'email' => $user->email,
            ],
            'redirect_url' => env("APP_ENV") === 'production' ? "https://google.com" : "http://localhost:5173/checkout/success",
            'success_redirect_url' => env("APP_ENV") === 'production' ? "https://google.com" : "http://localhost:5173/checkout/success",
        ];

        $invoiceApi = new InvoiceApi();
        $createInvoiceRequest = new CreateInvoiceRequest($params);

        try {
            $invoice = $invoiceApi->createInvoice($createInvoiceRequest);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create invoice: ' . $e->getMessage()], 500);
        }

        if (!$invoice) {
            return response()->json(['error' => 'Failed to create invoice'], 500);
        }

        return response()->json([
            'message' => 'Checkout successful!',
            'invoice_url' => $invoice->getInvoiceUrl(),
            'invoice_id' => $invoice->getId(),
        ]);
    }
}
