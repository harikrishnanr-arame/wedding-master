<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Models\Template;
use App\Models\PublishedTemplate;


Route::get('/', [HomeController::class, 'home'])->name('home');

/* Authentication*/

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/* Password Reset*/

Route::get('/forgot-password', [PasswordController::class, 'forgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'sendLink'])->name('password.email');

Route::get('/reset-password/{token}', [PasswordController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');

/* Google OAuth*/

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

/* User Dashboard*/

Route::prefix('dashboard')
    ->middleware('auth')
    ->name('dashboard.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'profile'])->name('profile');
        Route::get('/templates', [DashboardController::class, 'templates'])->name('templates');
        Route::get('/payments', [DashboardController::class, 'payments'])->name('payments');

});

/* Admin Panel*/

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/manage-content', [AdminController::class, 'content'])->name('content');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

        // Users
        Route::get('/users/list', [AdminController::class, 'getUsers'])->name('users.list');
        Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
        Route::delete('/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // Payments
        Route::get('/payments/list', [AdminController::class, 'getPayments'])->name('payments.list');
        Route::delete('/payments/delete/{id}', [AdminController::class, 'deletePayment'])->name('payments.delete');

        // Templates
        Route::post('/templates/store', [AdminController::class, 'storeTemplate'])->name('templates.store');
        Route::delete('/templates/delete/{id}', [AdminController::class, 'deleteTemplate'])->name('templates.delete');
        Route::patch('/templates/toggle/{id}', [AdminController::class, 'toggleTemplate'])->name('templates.toggle');

        //Settings
        Route::post('/change-password', [AdminController::class, 'changePassword'])->name('change-password');
});

/* Template Edit Page*/
// Route::get('/template-edit/{id}', function ($id) { return view('templateEditPage', compact('id'));})->name('template.edit');

Route::post('/template/create/{id}', [DashboardController::class, 'createTemplate'])
    ->middleware('auth')
    ->name('template.create');

Route::get('/template-edit/{id}', [DashboardController::class, 'editTemplate'])
    ->middleware('auth')
    ->name('template.edit');

Route::post('/template-save/{id}', [DashboardController::class, 'saveTemplate'])
    ->middleware('auth')
    ->name('template.save');
Route::post('/template/upload-image', [DashboardController::class, 'uploadImage'])
    ->middleware('auth')
    ->name('template.upload');

    Route::get('/phpinfo-test', function () {
    phpinfo();
});

// Route::get('/published/{route}', function($route) {
//     $published = \App\Models\PublishedTemplate::where('route', $route)->firstOrFail();
//     $content = json_decode($published->content_json, true);

//     $template = $published->template;
//     if (!$template || !$template->html_file) {
//         abort(404, 'Original template file not found.');
//     }

//     $templateFile = 'templates.' . pathinfo($template->html_file, PATHINFO_FILENAME);

//     return view($templateFile, compact('content'));
// })
// ->where('route', '.*')
// ->name('published.show');

// Route::get('/published/{route}', function($route) {
//     $published = PublishedTemplate::where('route', $route)->firstOrFail();
//     $content = json_decode($published->content_json, true);

//     return view('templates.wedding', compact('content'));
// })->where('route', '.*')->name('published.show');

Route::get('/published/{route}', function($route) {
    $published = PublishedTemplate::where('route', $route)->firstOrFail();
    $content = json_decode($published->content_json, true);

    if(!$published->template || !$published->template->html_file) {
        abort(404, 'Original template not found.');
    }

    $templateFile = 'templates/' . $published->template->html_file;

    return view($templateFile, compact('content'));
})->where('route', '.*')->name('published.show');

use App\Http\Controllers\PublishedTemplateController;

// Route::get('/published/{route}', [PublishedTemplateController::class, 'show'])
//     ->name('published.show');

Route::get('/published/{route}', [PublishedTemplateController::class, 'show'])
    ->where('route', '.*') // allow anything, including slashes
    ->name('published.show');

Route::get('/published/{route}', [DashboardController::class, 'viewPublishedSite'])->name('site.published');    