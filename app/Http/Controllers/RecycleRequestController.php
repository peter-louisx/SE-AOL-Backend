<?php

namespace App\Http\Controllers;

use App\Models\RecycleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecycleRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $requests = RecycleRequest::where('customer_id', $user->customer->id)->get();

        return response()->json($requests);
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $request = RecycleRequest::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$request) {
            return response()->json(['message' => 'Recycle request not found'], 404);
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
            'message_id' => 'required|exists:messages,id',
            'req_status' => 'required|string',
            'delivery_type' => 'required|string',
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
}
