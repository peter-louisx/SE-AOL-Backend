<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::where('customer_id', Auth::id())->get();
        return response()->json($vouchers);
    }

    public function show($id)
    {
        $voucher = Voucher::where('id', $id)->where('customer_id', Auth::id())->first();
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }
        return response()->json($voucher);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'terms' => 'required|string',
            'valid_until' => 'required|date',
        ]);

        $voucher = Voucher::create([
            'customer_id' => Auth::id(),
            'title' => $request->title,
            'terms' => $request->terms,
            'valid_until' => $request->valid_until,
        ]);

        return response()->json([
            'message' => 'Voucher created successfully!',
            'id' => $voucher->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::where('id', $id)->where('customer_id', Auth::id())->first();
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|string',
            'terms' => 'sometimes|string',
            'valid_until' => 'sometimes|date',
        ]);

        $voucher->update($request->only(['title', 'terms', 'valid_until']));

        return response()->json([
            'message' => 'Voucher updated successfully!',
            'voucher' => $voucher,
        ]);
    }

    public function destroy($id)
    {
        $voucher = Voucher::where('id', $id)->where('customer_id', Auth::id())->first();
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $voucher->delete();

        return response()->json(['message' => 'Voucher deleted successfully!']);
    }
}
