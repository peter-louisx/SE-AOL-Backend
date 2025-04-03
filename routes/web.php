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
Route::get('/user', [AuthController::class, 'user']); # get user info

# Customer
Route::post('/customerRegister', [CustomerController::class, 'customerRegister']);
Route::post('/addCustomerAddress', [CustomerController::class, 'addCustomerAddress']);

# Seller
Route::post('/sellerRegister', [SellerController::class, 'sellerRegister']);
Route::post('/addBankAccount', [SellerController::class, 'addBankAccount']);
#######################################


// product_tags 
Route::get('/product-tags', [ProductTagController::class, 'index']);
Route::get('/product-tags/{id}', [ProductTagController::class, 'show']);
Route::post('/store-product-tag', [ProductTagController::class, 'store']);
Route::put('/update-product-tag/{id}', [ProductTagController::class, 'update']);
Route::delete('/delete-product-tag/{id}', [ProductTagController::class, 'destroy']);



//brands
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{id}', [BrandController::class, 'show']);
Route::post('/store-brand', [BrandController::class, 'store']);
Route::put('/update-brand/{id}', [BrandController::class, 'update']);
Route::delete('/delete-brand/{id}', [BrandController::class, 'destroy']);

//category
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/store-category', [CategoryController::class, 'store']);
Route::put('/update-category/{id}', [CategoryController::class, 'update']);
Route::delete('/delete-category/{id}', [CategoryController::class, 'destroy']);

//reviews
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::post('/store-reviews', [ReviewController::class, 'store']);
Route::put('/update-reviews/{id}', [ReviewController::class, 'update']);
Route::delete('/delete-reviews/{id}', [ReviewController::class, 'destroy']);

//orders
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/store-order', [OrderController::class, 'store']);
Route::put('/update-order/{id}', [OrderController::class, 'update']);
Route::delete('/delete-order/{id}', [OrderController::class, 'destroy']);

//carts
Route::get('/carts', [CartController::class, 'index']);
Route::get('/carts/{id}', [CartController::class, 'show']);
Route::post('/store-cart', [CartController::class, 'store']);
Route::put('/update-cart/{id}', [CartController::class, 'update']);
Route::delete('/delete-cart/{id}', [CartController::class, 'destroy']);

//products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/store-product', [ProductController::class, 'store']);
Route::put('/update-product/{id}', [ProductController::class, 'update']);
Route::delete('/delete-product/{id}', [ProductController::class, 'destroy']);
