<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\OrderApiController;

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:api')->group(function () {
    // Browse books
    Route::get('/books', [BookApiController::class, 'index']);
    Route::get('/books/{book}', [BookApiController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Protected API Routes (Require Authentication via Sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Authenticated user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Book management (write operations)
    Route::middleware('throttle:sensitive')->group(function () {
        Route::post('/books', [BookApiController::class, 'store']);
        Route::put('/books/{book}', [BookApiController::class, 'update']);
        Route::delete('/books/{book}', [BookApiController::class, 'destroy']);
    });

    // Order endpoints (tiered rate limiting)
    Route::middleware('throttle:api')->group(function () {
        Route::get('/orders', [OrderApiController::class, 'index']);
        Route::post('/orders', [OrderApiController::class, 'store']);
    });
});