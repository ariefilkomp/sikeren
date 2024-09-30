<?php

use App\Http\Controllers\api\MessageApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/save-message', [MessageApiController::class, 'store']);
Route::post('/anoman-receive', [MessageApiController::class, 'anomanReceive']);