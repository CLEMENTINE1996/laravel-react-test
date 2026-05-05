<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication route for user login
Route::post('/v1/login', [AuthController::class, 'login']);

// Protected routes for ticket management
Route::middleware('auth:sanctum')->group(function () { 
    Route::prefix('v1')->group(function () {
        Route::apiResource('tickets', TicketController::class);
    });
});