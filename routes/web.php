<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/token-create-test', function () {
    $token = User::where('email', 'test@example.com')->first()->createToken('test-token')->plainTextToken;
    return response()->json(['token' => $token]);
});

Route::get('/test-auth', function (Request $request) {
    return response()->json(['message' => 'Hello World!']);
})->middleware('auth:sanctum');
