<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\WhatWeDoController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\CategoryController;




Route::middleware('auth')->group(function () {
    Route::get('slider/search', [SliderController::class, 'search'])->name('sliders.search');
    Route::get('services/search', [ServiceController::class, 'search'])->name('services.search');
    Route::get('whatwedo/search', [WhatWeDoController::class, 'search'])->name('whatwedo.search');
    Route::get('portfolio/search', [PortfolioController::class, 'search'])->name('portfolio.search');
    Route::get('category/search', [CategoryController::class, 'search'])->name('category.search');
    
});