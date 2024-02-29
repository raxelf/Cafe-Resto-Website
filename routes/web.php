<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/get-random-product', [HomeController::class, 'getRandomProduct']);
Route::get('/menu', [HomeController::class, 'menu'])->name('home.menu');

Route::post('/add-to-cart', [HomeController::class, 'addToCart']);
Route::get('/get-cart-items', [HomeController::class, 'getCartItems']);
Route::get('/order-details/{orderId}', [OrderController::class, 'showOrderDetails'])->name('order.details');
Route::delete('/cart/{id}', [HomeController::class, 'destroyCart']);
Route::put('/cart/addQuantity/{itemId}', [HomeController::class, 'addQuantity']);
Route::put('/cart/decreaseQuantity/{itemId}', [HomeController::class, 'decreaseQuantity']);

Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place-order');
Route::get('thankyou/{invoice}', [HomeController::class, 'thankYouPage'])->name('thankyou');

Route::get('admin', [AuthController::class, 'index'])->name('login');
Route::post('admin', [AuthController::class, 'login']);
Route::get('admin/logout', [AuthController::class, 'logout']);

Route::group([
    'prefix' => 'admin',
], function () {
    // Kategori
    Route::get('/data/kategori', [CategoryController::class, 'list']);
    Route::get('/data/kategori/create', [CategoryController::class, 'create']);
    Route::get('/data/kategori/edit/{id}', [CategoryController::class, 'edit']);

    // Barang
    Route::get('/data/barang', [ProductController::class, 'list']);
    Route::get('/data/barang/create', [ProductController::class, 'create']);
    Route::get('/data/barang/edit/{id}', [ProductController::class, 'edit']);

    // Pesanan
    Route::get('/pesanan', [OrderController::class, 'list']);

    // Laporan
    Route::get('/laporan', [ReportController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

});