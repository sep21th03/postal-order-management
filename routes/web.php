<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
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

Route::get('/login', function () {
    return view('auth.login');
})->name('auth.login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('order/add', [OrderController::class, 'store'])->name('order.store');
    Route::get('order/detail/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/order/delete', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::post('/order/update', [OrderController::class, 'update'])->name('order.update');
});
