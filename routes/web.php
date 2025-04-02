<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

##################################### 
# User
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);

# Customer
Route::post('/customerRegister', [UserController::class, 'customerRegister']);

# Seller
Route::post('/sellerRegister', [UserController::class, 'sellerRegister']);

# Vendor
Route::post('/vendorRegister', [UserController::class, 'vendorRegister']);

#######################################