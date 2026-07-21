<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});