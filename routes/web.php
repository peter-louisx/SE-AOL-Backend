<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\RecycleRequestController;

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

// User
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

// Customer
Route::post('/customerRegister', [CustomerController::class, 'customerRegister']);
Route::post('/add-customer-address', [CustomerController::class, 'addCustomerAddress'])->middleware('auth:sanctum');
Route::post('/edit-customer-profile', [CustomerController::class, 'editCustomerProfile'])->middleware('auth:sanctum');
Route::post('/customer-profile-picture', [CustomerController::class, 'customerProfilePicture'])->middleware('auth:sanctum');
Route::post('/get-customer-profile', [CustomerController::class, 'getCustomerProfile'])->middleware('auth:sanctum');

// Seller
Route::post('/sellerRegister', [SellerController::class, 'sellerRegister']);
Route::post('/edit-seller-profile', [SellerController::class, 'editSellerProfile'])->middleware('auth:sanctum');
Route::post('/seller-profile-picture', [SellerController::class, 'sellerPictureProfile'])->middleware('auth:sanctum');

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


//voucher
Route::get('/vouchers', [VoucherController::class, 'index']);
Route::get('/vouchers/{id}', [VoucherController::class, 'show']);
Route::post('/store-voucher', [VoucherController::class, 'store']);
Route::put('/update-voucher/{id}', [VoucherController::class, 'update']);
Route::delete('/delete-voucher/{id}', [VoucherController::class, 'destroy']);

//challenge
Route::get('/challenges', [ChallengeController::class, 'index']);
Route::get('/challenges/{id}', [ChallengeController::class, 'show']);
Route::post('/store-challenge', [ChallengeController::class, 'store']);
Route::put('/update-challenge/{id}', [ChallengeController::class, 'update']);
Route::delete('/delete-challenge/{id}', [ChallengeController::class, 'destroy']);

//vendor
Route::get('/vendors', [VendorController::class, 'index']);
Route::get('/vendors/{id}', [VendorController::class, 'show']);
Route::post('/store-vendor', [VendorController::class, 'store']);
Route::put('/update-vendor/{id}', [VendorController::class, 'update']);
Route::delete('/delete-vendor/{id}', [VendorController::class, 'destroy']);

//message
Route::get('/messages', [MessageController::class, 'index']);
Route::get('/messages/{id}', [MessageController::class, 'show']);
Route::post('/store-message', [MessageController::class, 'store']);
Route::put('/update-message/{id}', [MessageController::class, 'update']);
Route::delete('/delete-message/{id}', [MessageController::class, 'destroy']);

//recycle_request
Route::get('/recycle-requests', [RecycleRequestController::class, 'index']);
Route::get('/recycle-requests/{id}', [RecycleRequestController::class, 'show']);
Route::post('/store-recycle-request', [RecycleRequestController::class, 'store']);
Route::put('/update-recycle-request/{id}', [RecycleRequestController::class, 'update']);
Route::delete('/delete-recycle-request/{id}', [RecycleRequestController::class, 'destroy']);


//blog
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::post('/store-blog', [BlogController::class, 'store']);
Route::put('/update-blog/{id}', [BlogController::class, 'update']);
Route::delete('/delete-blog/{id}', [BlogController::class, 'destroy']);


Route::get('/get-csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});