<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn(Request $request) => $request->user())->middleware('auth:sanctum');

Route::get('/data/{table}', App\Http\Controllers\Api\ApiController::class)
    ->middleware('throttle:api')
    ->name('api.data');
