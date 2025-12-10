<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('/assets', [AssetController::class, 'index']);
    Route::post('/assets', [AssetController::class, 'store']);

    Route::get('/heirs', [HeirController::class, 'index']);
    Route::post('/heirs', [HeirController::class, 'store']);

    Route::get('/calculate', [FaraidController::class, 'calculate']);
});
