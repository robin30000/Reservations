<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/reservations', [ReservationController::class, 'store']);

#Route::post('/reservations', [ReservationController::class, 'store'])->middleware('auth:api');

