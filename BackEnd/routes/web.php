<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hakim\StudentEnrollController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]
                            
                            ###############AL GIQM ROUTE ########

 Route::any('/hakim', [StudentEnrollController::class, 'filterEnrollments'])->name('filterEnrollments');
Route::get('/faculty', [StudentEnrollController::class, 'faculty'])->name('faculty');
Route::get('/faculty/{faculty:id}', [StudentEnrollController::class, 'program'])->name('program');
