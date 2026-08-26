<?php

use Illuminate\Support\Facades\Route;

use App\Models\Supplier;
use App\Models\Product;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SettingController;

// Rutas Públicas / Autenticación
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm']); // Permite GET en /login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware(['auth', 'active.user'])->group(function () {

    Route::get('/check-status', function () {
        return response()->json(['status' => 'active']);
    });

    Route::get('/dashboard', function () { 
        $products = Product::all(); 
        $suppliers = Supplier::all(); 
        return view('dashboard', compact('products', 'suppliers')); 
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cash-register', function () { return view('cash-register.index'); })->name('cash-register.index');

    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotations/{id}/download', [QuotationController::class, 'download'])->name('quotations.download');
    Route::delete('/quotations/{id}', [QuotationController::class, 'destroy'])->name('quotations.destroy');

    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::delete('/purchase-orders/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    Route::get('/purchase-orders/{id}/pdf', [PurchaseOrderController::class, 'streamPdf'])->name('purchase-orders.pdf');
    Route::patch('/purchase-orders/{id}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');

    Route::get('/settings/profits', [SettingController::class, 'profits'])->name('settings.profits');
    Route::get('/settings/vat', [SettingController::class, 'vat'])->name('settings.vat');
    Route::put('/settings/vat/{vat}', [SettingController::class, 'updateVat'])->name('settings.vat.update');
});

Route::middleware(['auth', 'admin', 'active.user'])->group(function () {

    Route::get('/check-status', function () {
        return response()->json(['status' => 'active']);
    });

    Route::get('/inventory', [ProductController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [ProductController::class, 'store'])->name('inventory.store');
    Route::put('/inventory/{id}', [ProductController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [ProductController::class, 'destroy'])->name('inventory.destroy');
    Route::patch('/inventory/{id}/disable', [ProductController::class, 'disable'])->name('inventory.disable');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/disable', [UserController::class, 'disable'])->name('users.disable');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
});