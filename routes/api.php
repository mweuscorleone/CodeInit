<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthenticationController::class, 'store']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->group(function(){
     Route::post('/logout', [AuthenticationController::class, 'logout']);

});
Route::put('/update/{id}', [AuthenticationController::class, 'update']);
Route::delete('/delete/{id}', [AuthenticationController::class, 'destroy']);
Route::post('/index', [AuthenticationController::class, 'index']);
Route::post('/user/{id}', [AuthenticationController::class, 'userFind']);

