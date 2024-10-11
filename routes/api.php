<?php

use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\VaccineCenterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/vaccine-centers', [App\Http\Controllers\Api\VaccineCenterController::class, 'index']);
Route::get('/users', [VaccineCenterController::class, 'getUsers']);
Route::post('/register', [VaccineCenterController::class, 'register']);
Route::get('/status/{nid}', [SearchController::class, 'searchStatus']);