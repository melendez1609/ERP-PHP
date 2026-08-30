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
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\ScheduleController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware(['auth', 'active.user'])->group(function () {

    Route::get('/dashboard', function () { 
        $products = Product::with('batches')->get(); 
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

    Route::get('/settings/vat', [SettingController::class, 'vat'])->name('settings.vat');
    Route::post('/settings/vat', [SettingController::class, 'storeVat'])->name('settings.vat.store');
    Route::delete('/settings/vat/{id}', [SettingController::class, 'destroyVat'])->name('settings.vat.destroy');

    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
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
    Route::get('/inventory/{product}/batches', [ProductController::class, 'getBatches'])->name('inventory.batches');
    Route::get('/inventory/active-products', [ProductController::class, 'getActiveProducts'])->name('inventory.activeProducts');
    Route::post('/inventory/add-stock', [ProductController::class, 'addStock'])->name('inventory.addStock');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/disable', [UserController::class, 'disable'])->name('users.disable');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::post('/barcodes/generate', [BarcodeController::class, 'generate'])->name('barcodes.generate');
    Route::get('/barcodes/search', [BarcodeController::class, 'search'])->name('barcodes.search');
});