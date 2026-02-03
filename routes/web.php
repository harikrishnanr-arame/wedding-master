<?php
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AdminController;

//home
Route::get('/', [HomeController::class, 'home']);

//register
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

//login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'login']);

//dashboard route with protection
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.profile');
    })->name('dashboard');

    Route::get('/dashboard/templates', function () {
        return view('dashboard.templates');
    })->name('dashboard.templates');

    Route::get('/dashboard/payments', function () {
    return view('dashboard.payments');
    })->name('dashboard.payments');

});

//logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

//forgot password
Route::get('/forgot-password', [PasswordController::class, 'forgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'sendLink'])->name('password.email');

//password reset
Route::get('/reset-password/{token}', [PasswordController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');

//for google authentication
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

//admin dashboard
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
});

// Admin dashboard routes
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('users');

    Route::get('/manage-content', [AdminController::class, 'content'])->name('content');

    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');

    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    // Users
    Route::get('/users/list', [AdminController::class, 'getUsers'])->name('users.list');
    Route::delete('/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');

    // Payments
    Route::get('/payments/list', [AdminController::class, 'getPayments'])->name('payments.list');
    Route::delete('/payments/delete/{id}', [AdminController::class, 'deletePayment'])->name('payments.delete');
});


