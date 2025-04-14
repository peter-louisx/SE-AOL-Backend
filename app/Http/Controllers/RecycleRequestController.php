<?php

namespace App\Http\Controllers;

use App\Models\RecycleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecycleRequestController extends Controller
{
    public function index()
    {
        $requests = RecycleRequest::with(['vendor', 'customer', 'message'])->get();
        return response()->json($requests);
    }

    public function show($id)
    {
        $request = RecycleRequest::with(['vendor', 'customer', 'message'])->find($id);

        if (!$request) {
            return response()->json(['message' => 'Recycle request not found'], 404);
        }

        return response()->json($request);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'message_id' => 'required|exists:messages,id',
            'req_status' => 'required|in:P,C,X', // P: pending, C: completed, X:canceled
            'delivery_type' => 'required|in:D,P', // D: delivery, P:pickup
            'total_pay' => 'required|integer|min:0',
        ]);

        $recycleRequest = RecycleRequest::create([
            'vendor_id' => $request->vendor_id,
            'customer_id' => Auth::user()->id,
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
        $recycleRequest = RecycleRequest::find($id);

        if (!$recycleRequest) {
            return response()->json(['message' => 'Recycle request not found'], 404);
        }

        $request->validate([
            'vendor_id' => 'sometimes|exists:vendors,id',
            'message_id' => 'sometimes|exists:messages,id',
            'req_status' => 'sometimes|in:P,C,X',
            'delivery_type' => 'sometimes|in:D,P',
            'total_pay' => 'sometimes|integer|min:0',
        ]);

        $recycleRequest->update($request->only([
            'vendor_id',
            'message_id',
            'req_status',
            'delivery_type',
            'total_pay'
        ]));

        return response()->json([
            'message' => 'Recycle request updated successfully!',
            'data' => $recycleRequest,
        ]);
    }

    public function destroy($id)
    {
        $recycleRequest = RecycleRequest::find($id);

        if (!$recycleRequest) {
            return response()->json(['message' => 'Recycle request not found'], 404);
        }

        $recycleRequest->delete();

        return response()->json(['message' => 'Recycle request deleted successfully!']);
    }
}
