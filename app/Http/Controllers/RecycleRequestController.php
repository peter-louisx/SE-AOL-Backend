<?php

namespace App\Http\Controllers;

use App\Models\RecycleRequest;
use App\Models\UpcycleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;


class RecycleRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $requests = UpcycleRequest::where('customer_id', $user->customer->id)->get();

        $data = $requests->map(function ($request) {
            return [
                'id' => $request->id,
                'status' => $request->req_status,
                'request_date' => $request->created_at ? $request->created_at->toDateTimeString() : null,
                'notes' => $request->notes,
                'total_payment' => $request->total_pay,
                'image_url' => "https://atlas-content-cdn.pixelsquid.com/stock-images/full-trash-bag-black-garbage-qvOEM2A-600.jpg",
                'code' => $request->id,
            ];
        });


        return response()->json($data);
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $request = UpcycleRequest::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$request) {
            return response()->json(['message' => 'Recycle request not found'], 404);
        }

        $data = [
            'id' => $request->id,
            'status' => $request->req_status,
            'request_date' => $request->created_at ? $request->created_at->toDateTimeString() : null,
            'notes' => $request->notes,
            'total_payment' => $request->total_pay,
            'image_url' => "https://atlas-content-cdn.pixelsquid.com/stock-images/full-trash-bag-black-garbage-qvOEM2A-600.jpg",
            'code' => $request->id,
        ];

        return response()->json($data);
    }

    public function sendCycleRequest(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $recycleRequest = UpcycleRequest::create([
            'customer_id' => $user->customer->id,
            'vendor_id' => 2,
            'req_status' => "On Review",
            'shipping_method' => "Pickup",
            'total_pay' => 0,
            'notes' => $request->notes,
            'pickup_date' => now(),
        ]);

        return response()->json([
            'message' => 'Recycle request sent successfully!',
            'id' => $recycleRequest->id,
        ], 201);
    }

    public function updateRecycleRequestStatus(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $recycleRequest = RecycleRequest::where('id', $id)->first();

        if (!$recycleRequest) {
            return response()->json(['message' => 'Recycle request not found'], 404);
        }

        $request->validate([
            'req_status' => 'required|string', // X cancelled, P pending, C completed
            'total_pay' => 'required|integer',
        ]);

        $recycleRequest->update([
            'req_status' => $request->req_status,
            'total_pay' => $request->total_pay,
        ]);

        return response()->json([
            'message' => 'Recycle request status updated successfully!',
            'data' => $recycleRequest,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'message_id' => 'required|exists:messages,id',
            'req_status' => 'required|string', // X cancelled, P penidng, C comleted
            'delivery_type' => 'required|string', // D , delivery, P, pickup
            'total_pay' => 'required|integer',
        ]);

        $recycleRequest = RecycleRequest::create([
            'customer_id' => $user->customer->id,
            'vendor_id' => $request->vendor_id,
            'message_id' => $request->message_id,
            'req_status' => $request->req_status,
            'delivery_type' => $request->delivery_type,
            'total_pay' => $request->total_pay,
        ]);

        return response()->json([
            'message' => 'Recycle request created successfully!',
            'id' => $recycleRequest->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $recycleRequest = RecycleRequest::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$recycleRequest) {
            return response()->json(['message' => 'Recycle request not found'], 404);
        }

        $request->validate([
            'vendor_id' => 'sometimes|exists:vendors,id',
            'message_id' => 'sometimes|exists:messages,id',
            'req_status' => 'sometimes|string',
            'delivery_type' => 'sometimes|string',
            'total_pay' => 'sometimes|integer',
        ]);

        $recycleRequest->update($request->only(['vendor_id', 'message_id', 'req_status', 'delivery_type', 'total_pay']));

        return response()->json([
            'message' => 'Recycle request updated successfully!',
            'data' => $recycleRequest,
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $recycleRequest = RecycleRequest::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$recycleRequest) {
            return response()->json(['message' => 'Recycle request not found'], 404);
        }

        $recycleRequest->delete();

        return response()->json(['message' => 'Recycle request deleted successfully!']);
    }

    public function pay(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $recycleRequest = UpcycleRequest::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        // Logic to process payment goes here
        // For example, you might integrate with a payment gateway
        if (!$recycleRequest) {
            return response()->json(['message' => 'Recycle request not found'], 404);
        }

        $shippingFee = 1000;
        $serviceFee = 2000;
        $deliveryFee = $deliveryOptions[$request->input('delivery_option', 'economy')] ?? 3000;
        $amount = $recycleRequest->total_pay;

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
            return response()->json(['error' => 'Payment processing failed: ' . $e->getMessage()], 500);
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
