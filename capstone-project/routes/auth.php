<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::post('/login', [LoginController::class, 'index']);


/**
 *
 * route create with endpoint /login
 * route registration in bootstrap app - then() method a - using prefix `api/auth`
 * postman open - header a add - Accept: application/json
 * create controller
 * create seeder for user create
 * create request file - form er field er validate korbo.
 * auth::attempt diye
 */

