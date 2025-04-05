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

// User
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']); # get user info

// Customer
Route::post('/customer-register', [CustomerController::class, 'customerRegister']);
Route::post('/show-customer-address', [CustomerController::class, 'getCustomerAddresses'])->middleware('auth:sanctum');
Route::post('/add-customer-address', [CustomerController::class, 'addCustomerAddress'])->middleware('auth:sanctum');
Route::post('/edit-customer-address/{id}', [CustomerController::class, 'editCustomerAddress'])->middleware('auth:sanctum');
Route::post('/delete-customer-address/{id}', [CustomerController::class, 'deleteCustomerAddress'])->middleware('auth:sanctum');
Route::post('/edit-customer-profile', [CustomerController::class, 'editCustomerProfile'])->middleware('auth:sanctum');
Route::post('/customer-profile-picture', [CustomerController::class, 'customerProfilePicture'])->middleware('auth:sanctum');

// Seller
Route::post('/seller-register', [SellerController::class, 'sellerRegister']);
Route::post('/edit-seller-profile', [SellerController::class, 'editSellerProfile'])->middleware('auth:sanctum');
Route::post('/seller-profile-picture', [SellerController::class, 'sellerPictureProfile'])->middleware('auth:sanctum');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
