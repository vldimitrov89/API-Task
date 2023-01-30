<?php

use Pecee\SimpleRouter\SimpleRouter;
use Api\app\Controllers\UsersController;
use Api\app\Controllers\HospitalsController;

SimpleRouter::get('/', function () {
    return json_encode('Hello world');
});

//routes for users
SimpleRouter::get('/users', [UsersController::class, 'index']);
SimpleRouter::get('/users/{id}', [UsersController::class, 'show']);
SimpleRouter::post('/users/create', [UsersController::class, 'store']);
SimpleRouter::post('/users/update/{id}', [UsersController::class, 'update']);
SimpleRouter::delete('/users/delete/{id}', [UsersController::class, 'destroy']);

//routes for hospitals
SimpleRouter::get('/hospitals', [HospitalsController::class, 'index']);
SimpleRouter::get('/hospitals/{id}', [HospitalsController::class, 'show']);
SimpleRouter::post('/hospitals/create', [HospitalsController::class, 'store']);
SimpleRouter::post('/hospitals/update/{id}', [HospitalsController::class, 'update']);
SimpleRouter::delete('/hospitals/delete/{id}', [HospitalsController::class, 'destroy']);
