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
Route::get('/service/{id}', [AppointmentController::class, 'service'])
    ->name('appt.service');
Route::get('/service/{id}/user/{userId}', [AppointmentController::class, 'datetimepicker'])
    ->name('appt.user');
Route::get('/service/{id}/user/{userId}/time/{unixTimestamp}', [AppointmentController::class, 'datetimepicker'])
    ->name('appt.time');
Route::get('/service/{id}/user/{userId}/time/{unixTimestamp}/confirm', [AppointmentController::class, 'confirm'])
    ->name('appt.confirm');
Route::post('/confirm', [AppointmentController::class, 'confirmPost']);
Route::get('/thank-you', [AppointmentController::class, 'thankyou'])->name('appt.thankyou');
