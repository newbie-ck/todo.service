<?php

use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
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

$version = 'v1';

Route::group(['middleware' => 'api', 'prefix' => $version], function () {
    Route::prefix('todos')->group(function () {
        Route::get('/', [TodoController::class, 'index']);
        Route::get('/{uuid}', [TodoController::class, 'show']);
        Route::post('/', [TodoController::class, 'store']);
        Route::put('/{uuid}', [TodoController::class, 'update']);
        Route::patch('/{uuid}/status', [TodoController::class, 'updateStatus']);
        Route::delete('/{uuid}', [TodoController::class, 'destroy']);
    });
});