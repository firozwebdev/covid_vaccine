<?php

use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\VaccineCenterController;
use App\Http\Controllers\Api\VaccineScheduleController;
use Illuminate\Support\Facades\Route;


Route::get('/vaccine-centers', [VaccineCenterController::class, 'getVaccineCenters']);
Route::get('/users', [VaccineCenterController::class, 'getUsers']);
Route::get('/status/{nid}', [SearchController::class, 'searchStatus']);

Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/schedule', [VaccineScheduleController::class, 'scheduleVaccination']);