<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', [ImageController::class, 'index']);
Route::get('/images', [ImageController::class, 'show']);
Route::post('/upload', [ImageController::class, 'store']);