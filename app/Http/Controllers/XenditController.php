<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XenditController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // Verify signature if needed (Xendit supports HMAC signature)

        // Process invoice status
        if ($data['status'] === 'PAID') {
            // Mark order as paid
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }
}
