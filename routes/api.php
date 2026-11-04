<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PortfolioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Portfolio Public API Routes
|--------------------------------------------------------------------------
|
| Endpoint publik untuk mengambil data portofolio dan mengirim pesan kontak.
| POST /api/contact dilindungi oleh throttle:5,1 (maks 5 request per menit).
|
*/

Route::get('/portfolio', [PortfolioController::class, 'index']);

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1');
