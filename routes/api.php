<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
