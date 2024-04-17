<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExternalBookController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/external-books', [ExternalBookController::class, 'getExternalBooks']);
Route::post('/v1/books', [ExternalBookController::class, 'create']);
Route::get('/v1/books', [ExternalBookController::class, 'index']);
Route::patch('/v1/books', [ExternalBookController::class, 'updates']);
Route::delete('/v1/books/{id}', [ExternalBookController::class, 'destroy']);
Route::get('/v1/books/{id}', [ExternalBookController::class, 'show']);