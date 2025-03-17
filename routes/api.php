<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Get available seats for a trip between two stations
    Route::post('/available-seats', [BookingController::class, 'getAvailableSeats']);
    
    // Book a seat
    Route::post('/book-seat', [BookingController::class, 'bookSeat']);
    
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});