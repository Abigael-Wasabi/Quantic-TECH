<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;

/* Route::get('/', function () {
    return ['Laravel' => app()->version()];
}); */

require __DIR__ . '/auth.php';

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/generate-invoice', [InvoiceController::class, 'index']);
Route::get('/generate-invoice/{orderId}', [InvoiceController::class, 'generateInvoice']);
Route::get('/generate-invoice-pdf/{orderId}', [InvoiceController::class, 'generateInvoicePDF'])->name('generate.invoice.pdf');

Route::middleware(['auth'])->group(function () {
    Route::view('/customers', 'customers.index')->name('customers.index');
    Route::view('/products', 'products.index')->name('products.index');
    Route::view('/orders', 'orders.index')->name('orders.index');
    Route::view('/dashboard', 'dashboard.index')->name('dashboard');
});
