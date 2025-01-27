<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;


//Ruta raíz
Route::get('', [LoginController::class, 'showLoginForm'])->name('login');

// Ruta para mostrar el formulario de inicio de sesión
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Ruta para procesar el inicio de sesión
Route::post('login', [LoginController::class, 'login']);

// Ruta para cerrar sesión
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

// Ruta para mostrar el formulario de registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Ruta para procesar el registro
Route::post('register', [RegisterController::class, 'register']);