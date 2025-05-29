<?php

namespace App\Http\Controllers;

use App\Models\UpcycleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpcycleRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $requests = UpcycleRequest::where('customer_id', $user->customer->id)->get();

        return response()->json($requests);
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
            return response()->json(['message' => 'Upcycle request not found'], 404);
        }

        return response()->json($request);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'req_status' => 'required|string', // X: Cancelled, P: Pending, C: Completed
            'shipping_method' => 'required|string|max:20', // Pickup, Delivery, etc.
            'pickup_date' => 'required|date',
            'pickup_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
            'total_pay' => 'required|integer',
        ]);

        $upcycleRequest = UpcycleRequest::create([
            'customer_id' => $user->customer->id,
            'vendor_id' => $request->vendor_id,
            'req_status' => $request->req_status,
            'shipping_method' => $request->shipping_method,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'notes' => $request->notes,
            'total_pay' => $request->total_pay,
        ]);

        return response()->json([
            'message' => 'Upcycle request created successfully!',
            'id' => $upcycleRequest->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $upcycleRequest = UpcycleRequest::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$upcycleRequest) {
            return response()->json(['message' => 'Upcycle request not found'], 404);
        }

        $request->validate([
            'vendor_id' => 'sometimes|exists:vendors,id',
            'req_status' => 'sometimes|string',
            'shipping_method' => 'sometimes|string|max:20',
            'pickup_date' => 'sometimes|date',
            'pickup_time' => 'sometimes|date_format:H:i',
            'notes' => 'nullable|string',
            'total_pay' => 'sometimes|integer',
        ]);

        $upcycleRequest->update($request->only([
            'vendor_id', 'req_status', 'shipping_method', 'pickup_date',
            'pickup_time', 'notes', 'total_pay'
        ]));

        return response()->json([
            'message' => 'Upcycle request updated successfully!',
            'data' => $upcycleRequest,
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $upcycleRequest = UpcycleRequest::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$upcycleRequest) {
            return response()->json(['message' => 'Upcycle request not found'], 404);
        }

        $upcycleRequest->delete();

        return response()->json(['message' => 'Upcycle request deleted successfully!']);
    }
}
