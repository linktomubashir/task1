<?php
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailController;
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

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/search', [ProfileController::class, 'search'])->name('search');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/destroy', [ProfileController::class, 'destroy'])->name('destroy');
    });
    // Item
    Route::resource('item', ItemController::class);
    Route::prefix('item')->name('item.')->group(function () {
        Route::get('/search', [ItemController::class, 'show'])->name('show');
        Route::get('/search/model', [ItemController::class, 'searchModel'])->name('search_model');
        Route::get('/destroy/{id}', [ItemController::class, 'destroy'])->name('destroy');
        Route::post('/restore/{id}', [ItemController::class, 'restore'])->name('restore');
        Route::delete('/forceDelete/{id}', [ItemController::class, 'forceDelete'])->name('forceDelete');
    });

    // Models
    Route::resource('models', ModelController::class);
    Route::prefix('models')->name('models.')->group(function () {
        Route::get('/show', [ModelController::class, 'show'])->name('show');
        Route::get('/destroy/{id}', [ModelController::class, 'destroy'])->name('destroy');
        Route::post('/restore/{id}', [ModelController::class, 'restore'])->name('restore');
        Route::delete('/forceDelete/{id}', [ModelController::class, 'forceDelete'])->name('forceDelete');
    });

// Brand
    Route::resource('brands', BrandController::class);
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/show', [BrandController::class, 'show'])->name('show');
        Route::get('/destroy/{id}', [BrandController::class, 'destroy'])->name('destroy');
        Route::post('/restore/{id}', [BrandController::class, 'restore'])->name('restore');
        Route::delete('/forceDelete/{id}', [BrandController::class, 'forceDelete'])->name('forceDelete');
    });

    Route::resource('email', EmailController::class);

});

require __DIR__ . '/auth.php';
