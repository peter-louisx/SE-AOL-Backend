<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\AuthController;

#User Register dan log in
# User
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']); # get user info

# Customer
Route::post('/customer-register', [CustomerController::class, 'customerRegister']);
Route::post('/add-customer-address', [CustomerController::class, 'addCustomerAddress'])->middleware('auth:sanctum');

# Seller
Route::post('/seller-register', [SellerController::class, 'sellerRegister']);
Route::post('/add-bank-account', [SellerController::class, 'addBankAccount'])->middleware('auth:sanctum');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
