<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExternalBookController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/external-books', [ExternalBookController::class, 'getExternalBooks']);

Route::prefix('v1')->group(function () {
Route::post('/books', [ExternalBookController::class, 'create']);
Route::get('/books', [ExternalBookController::class, 'index']);
Route::patch('/books', [ExternalBookController::class, 'updates']);
Route::delete('/books/{id}', [ExternalBookController::class, 'destroy']);
Route::get('/books/{id}', [ExternalBookController::class, 'show']);
});
