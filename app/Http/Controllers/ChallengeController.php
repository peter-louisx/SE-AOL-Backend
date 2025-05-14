<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $challenges = Challenge::where('customer_id', $user->customer->id)->get();
        
        return response()->json($challenges);
    }

    public function show($id)
    {
        $user = Auth::user();
        

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $challenge = Challenge::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$challenge) {
            return response()->json(['message' => 'Challenge not found'], 404);
        }

        return response()->json($challenge);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
            'goal' => 'required|integer',
            'reward' => 'required|integer',
        ]);

        $challenge = Challenge::create([
            'customer_id' => $user->customer->id,
            'title' => $request->title,
            'desc' => $request->desc,
            'goal' => $request->goal,
            'reward' => $request->reward,
        ]);

        return response()->json([
            'message' => 'Challenge created successfully!',
            'id' => $challenge->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $challenge = Challenge::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$challenge) {
            return response()->json(['message' => 'Challenge not found'], 404);
        }


        $request->validate([
            'title' => 'sometimes|string',
            'desc' => 'sometimes|string',
            'goal' => 'sometimes|integer',
            'reward' => 'sometimes|integer',
        ]);
        $challenge->update($request->only(['title', 'desc', 'goal', 'reward']));

        return response()->json([
            'message' => 'Challenge updated successfully!',
            'challenge' => $challenge,
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        

        if (!$user || !$user->customer) {
            return response()->json(['error' => 'Pengguna belum login atau bukan customer'], 401);
        }

        $challenge = Challenge::where('id', $id)
            ->where('customer_id', $user->customer->id)
            ->first();

        if (!$challenge) {
            return response()->json(['message' => 'Challenge not found'], 404);
        }

        $challenge->delete();

        return response()->json(['message' => 'Challenge deleted successfully!']);
    }
}
