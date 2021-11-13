<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\HomeController::class);

Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::get('/user/{token}', [App\Http\Controllers\API\AuthController::class, 'user']);
Route::get('/users/{token}', [App\Http\Controllers\API\AuthController::class, 'users']);
Route::put('/user/{token}', [App\Http\Controllers\API\AuthController::class, 'change']);
Route::delete('/user/{token}', [App\Http\Controllers\API\AuthController::class, 'destroy']);
