<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

#User Register dan log in
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');;

Route::post('/customerRegister', [UserController::class, 'customerRegister']);

Route::post('/sellerRegister', [UserController::class, 'sellerRegister']);

Route::post('/vendorRegister', [UserController::class, 'vendorRegister']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
