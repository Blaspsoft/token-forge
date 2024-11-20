<?php

use Illuminate\Support\Facades\Route;
use Blaspsoft\TokenForge\Contracts\TokenForgeController;

Route::get('/api-tokens', [TokenForgeController::class, 'index'])->name('api-tokens.index');
Route::post('/api-tokens', [TokenForgeController::class, 'store'])->name('api-tokens.store');
Route::put('/api-tokens/{token}', [TokenForgeController::class, 'update'])->name('api-tokens.update');
Route::delete('/api-tokens/{token}', [TokenForgeController::class, 'destroy'])->name('api-tokens.destroy');
