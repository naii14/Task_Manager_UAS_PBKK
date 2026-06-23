<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me'])->middleware('token-abilities:access-api');

    Route::post('/auth/refresh', [AuthController::class, 'refresh'])->middleware('token-abilities:issue-access-token');

    // Protected tasks endpoint
    Route::get('/tasks', [TaskController::class, 'index'])->middleware('token-abilities:access-api');
});

