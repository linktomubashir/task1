<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PriceHistoryController;
use App\Http\Controllers\Api\AuditLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/price-history/{itemId}', [PriceHistoryController::class, 'index']);
Route::get('/audit/logs', [AuditLogController::class, 'index'])->name('audit.logs');