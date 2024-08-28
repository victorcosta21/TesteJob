<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PaymentController;

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

Route::get('/', [HomeController::class, 'index']);

Route::get('/clients', [ClientsController::class, 'index']);
Route::post('/clients/create', [ClientsController::class, 'store']);
// Route::put('/clients/update', [ClientsController::class, 'update']);
// Route::delete('/clients/delete', [ClientsController::class, 'delete']);

Route::get('/products', [ProductsController::class, 'index']);
Route::post('/products/create', [ProductsController::class, 'store']);
// Route::put('/clients/update', [ProductsController::class, 'update']);
// Route::delete('/clients/delete', [ProductsController::class, 'delete']);

Route::get('/payments', [PaymentController::class, 'index']);
Route::post('/payment/create', [PaymentController::class, 'store']);
Route::delete('/payment/delete', [PaymentController::class, 'delete']);
Route::get('/payment/show/{id}', [PaymentController::class, 'show']);



