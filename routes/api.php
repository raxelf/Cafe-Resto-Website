<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function(){
    Route::post('login', [AuthController::class, 'login']);
});

Route::group([
    'middleware' => 'api',
], function(){
    Route::resources([
        'categories' => CategoryController::class,
        'products' => ProductController::class,
        'orders' => OrderController::class,
    ]);

    Route::get('pesanan/baru', [OrderController::class, 'baru']);
    Route::get('pesanan/dikonfirmasi', [OrderController::class, 'dikonfirmasi']);
    Route::get('pesanan/dikemas', [OrderController::class, 'dikemas']);
    Route::get('pesanan/diantar', [OrderController::class, 'diantar']);
    Route::get('pesanan/selesai', [OrderController::class, 'selesai']);
    Route::post('pesanan/ubah_status/{order}', [OrderController::class, 'ubah_status']);

    Route::get('getCategoryId', [HomeController::class, 'getCategoryId']);

    Route::get('reports', [ReportController::class, 'get_reports']);

    Route::post('/pembayaran/webhook', [OrderController::class, 'webhook'])->middleware('verify.xendit.webhook');
});