<?php

use App\Reservations\Controllers\PublicReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/reservations', [PublicReservationController::class, 'store'])
    ->middleware('throttle:3,1');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
