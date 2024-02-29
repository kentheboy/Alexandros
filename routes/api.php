<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTestController;
use App\Http\Controllers\Alexandros\ProductsController;
use App\Http\Controllers\Alexandros\SchedulesController;

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

Route::get('/test', [ApiTestController::class, 'test']);

// Alexandros ProductsTable
Route::post('/products', [ProductsController::class, 'create']);
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{product}', [ProductsController::class, 'read']);
Route::patch('/products/{id}', [ProductsController::class, 'update']);
Route::delete('/products/{id}', [ProductsController::class, 'delete']);

// Alexandros ScheduleTable
Route::get('/schedule/search', [SchedulesController::class, 'search']);
Route::post('/schedule/create', [SchedulesController::class, 'create']);

