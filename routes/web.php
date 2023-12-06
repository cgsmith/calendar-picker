<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AppointmentController::class, 'index']);
Route::get('/appointment/service/{id}', [AppointmentController::class, 'service']);
Route::get('/appointment/service/{id}/{userId}', [AppointmentController::class, 'datetimepicker']);
Route::get('/appointment/service/{id}/{userId}/{unixTimestamp}', [AppointmentController::class, 'datetimepicker']);
Route::get('/appointment/service/{id}/{userId}/{unixTimestamp}/confirm', [AppointmentController::class, 'confirm']);
