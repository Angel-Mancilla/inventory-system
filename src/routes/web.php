<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('categories', CategoryController::class)->except('create','edit','show');
    Route::resource('brands', BrandController::class)->except('create','edit','show');
    Route::resource('conditions', ConditionController::class)->except('create','edit','show');
    Route::resource('warehouses', WarehouseController::class)->except('create','edit','show');
});

require __DIR__.'/settings.php';
