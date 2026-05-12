<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['resolveTenant'])->group(function(){
    Route::post('/auth/login', [\App\Http\Controllers\Api\AuthController::class,'login']);
    Route::post('/auth/register', [\App\Http\Controllers\Api\AuthController::class,'register']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/me', [\App\Http\Controllers\Api\AuthController::class,'me']);
        Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class,'show']);
        Route::put('/settings', [\App\Http\Controllers\Api\SettingsController::class,'update']);
        Route::get('/menu', [\App\Http\Controllers\Api\MenuController::class,'index']);
        Route::post('/menu/items', [\App\Http\Controllers\Api\MenuController::class,'store']);
        Route::patch('/menu/items/{item}', [\App\Http\Controllers\Api\MenuController::class,'update']);
        Route::delete('/menu/items/{item}', [\App\Http\Controllers\Api\MenuController::class,'destroy']);
        Route::get('/menu/recommended', [\App\Http\Controllers\Api\MenuController::class,'recommended']);
        Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class,'index']);
        Route::post('/orders', [\App\Http\Controllers\Api\OrderController::class,'store']);
        Route::post('/orders/{id}/pay', [\App\Http\Controllers\Api\OrderController::class,'pay']);
        Route::get('/kitchen/tickets', [\App\Http\Controllers\Api\KitchenController::class,'index']);
        Route::patch('/kitchen/tickets/{id}', [\App\Http\Controllers\Api\KitchenController::class,'update']);
        Route::get('/reports/sales', [\App\Http\Controllers\Api\ReportController::class,'sales']);
        Route::get('/reports/top-items', [\App\Http\Controllers\Api\ReportController::class,'topItems']);
        Route::get('/reports/cashiers', [\App\Http\Controllers\Api\ReportController::class,'cashiers']);
        // Route::post('/sync/push', [\App\Http\Controllers\Api\SyncController::class,'push']);
        // Route::post('/sync/pull', [\App\Http\Controllers\Api\SyncController::class,'pull']);
    });
});
