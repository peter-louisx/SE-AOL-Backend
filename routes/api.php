<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/customerRegister', [UserController::class, 'customerRegister']);

Route::post('/sellerRegister', [UserController::class, 'sellerRegister']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
