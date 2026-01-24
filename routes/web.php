<?php
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;

Route::get('/', [HomeController::class, 'home']);

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/profile', function () {
    return 'Profile Page';
})->middleware('auth');
