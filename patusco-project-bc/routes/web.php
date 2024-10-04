<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('api/', function () {
    return view('welcome');
});

Route::get('api/csrf-token', function () {
    $cookie = cookie('XSRF-TOKEN', csrf_token(), 60, '/', null, true, true, false, 'Lax');
    return response()->json(['csrf_token' => csrf_token()])
        ->cookie($cookie);
});

Route::post('/api/register', [UsersController::class, 'registerClient'])->withoutMiddleware('web');
Route::post('/api/login', [AuthController::class, 'login'])->withoutMiddleware('web');

Route::group(['middleware' => ['jwt.verify'], 'prefix' => '/api'], function () {
    Route::get('user-detail', [UsersController::class, 'getUserDetail']);
    Route::get('animal-types', [AnimalController::class, 'getAnimalTypes']);

    Route::group(['middleware' => ['jwt.verify.recepcionist',]], function () {
        Route::get('medics', [UsersController::class, 'getMedics']);
        Route::get('clients', [UsersController::class, 'getClients']);
        Route::delete('appointment/{appointment}', [AppointmentController::class, 'deleteAppointment'])->withoutMiddleware('web');
        Route::put('appointment-recepcionist', [AppointmentController::class, 'editAppointmentRecepcionist'])->withoutMiddleware('web');
        Route::post('recepcionist-appointment', [AppointmentController::class, 'createRecepcionistAppointment'])->withoutMiddleware('web');
    });

    Route::group(['middleware' => ['jwt.verify.staff']], function () {
        Route::get('animals', [AnimalController::class, 'getAnimals']);
        Route::post('appointments', [AppointmentController::class, 'getAppointments'])->withoutMiddleware('web');
        Route::get('appointments-by-medic/{medicId}', [AppointmentController::class, 'getAppointmentsByMedic']);
        Route::get('animals-by-client/{clientId}', [AnimalController::class, 'getAnimalByClientId']);
        Route::get('user-detail/{userId}', [UsersController::class, 'getUserDetailById']);
        Route::get('animal-detail/{animalId}', [AnimalController::class, 'getAnimalDetailById']);
        Route::put('appointment-medic', [AppointmentController::class, 'editAppointmentMedic'])->withoutMiddleware('web');
    });

    Route::group(['middleware' => ['jwt.verify.client']], function () {
        Route::post('client-appointment', [AppointmentController::class, 'createClientAppointment'])->withoutMiddleware('web');
    });
});
