<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PriceHistoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\SupportRequestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
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


Route::post('/submit-support-request', [SupportRequestController::class, 'store'])->name('support_request.store');
Route::post('/login', [AuthController::class, 'index']);
Route::get('/users', [AuthController::class, 'create'])->middleware('auth:sanctum');