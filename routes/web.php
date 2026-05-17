<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/login', [AuthController::class, 'loginPage'])->middleware('throttle:5,1')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/add', [AdminController::class, 'addProductForm'])->name('admin.add.product');
    Route::post('/admin/products/add', [AdminController::class, 'addProduct'])->name('admin.add.product.submit');
    Route::get('/admin/products/edit/{product}', [AdminController::class, 'editProduct'])->name('admin.edit.product');
    Route::put('/admin/products/edit/{product}', [AdminController::class, 'updateProduct'])->name('admin.update.product');
    Route::delete('/admin/products/delete/{product}', [AdminController::class, 'deleteProduct'])->name('admin.delete.product');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
