<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatererController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\CatererDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [HomeController::class, 'browse'])->name('browse');

/*
|--------------------------------------------------------------------------
| Auth Page Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/caterer/login', [AuthController::class, 'showCatererLogin'])->name('caterer.login');
Route::get('/caterer/register', [AuthController::class, 'showCatererRegister'])->name('caterer.register');
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');

/*
|--------------------------------------------------------------------------
| Auth Submission Routes (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Client Auth
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Caterer Auth
    Route::post('/caterer/login', [AuthController::class, 'catererLogin'])->name('caterer.login.post');
    Route::post('/caterer/register', [AuthController::class, 'catererRegister'])->name('caterer.register.post');
});

// Caterer Public Profile
Route::get('/caterer/{id}', [CatererController::class, 'show'])->whereNumber('id')->name('caterer.show');
Route::get('/caterer/{id}/reviews', [CatererController::class, 'reviews'])->whereNumber('id')->name('caterer.reviews');
Route::get('/caterer/{id}/gallery', [CatererController::class, 'gallery'])->whereNumber('id')->name('caterer.gallery');
Route::get('/caterer/{id}/about', [CatererController::class, 'about'])->whereNumber('id')->name('caterer.about');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Client Routes (Auth + Role: client)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [ClientDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/saved', [ClientDashboardController::class, 'saved'])->name('saved');
    Route::get('/messages', [ClientDashboardController::class, 'messages'])->name('messages');
    Route::get('/reviews', [ClientDashboardController::class, 'myReviews'])->name('reviews');
    Route::get('/settings', [ClientDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [ClientDashboardController::class, 'updateSettings'])->name('settings.update');
});

// Booking Routes (Client)
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/book/{catererId}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/book/{catererId}', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::delete('/booking/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// Review Routes (Client)
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/review/{catererId}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/review/{catererId}', [ReviewController::class, 'store'])->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| Caterer Routes (Auth + Role: caterer)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:caterer'])->prefix('dashboard')->name('caterer.')->group(function () {
    Route::get('/', [CatererDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [CatererDashboardController::class, 'bookings'])->name('bookings');
    Route::post('/bookings/{id}/confirm', [CatererDashboardController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::post('/bookings/{id}/reject', [CatererDashboardController::class, 'rejectBooking'])->name('bookings.reject');
    Route::get('/menu', [CatererDashboardController::class, 'menu'])->name('menu');
    Route::post('/menu', [CatererDashboardController::class, 'updateMenu'])->name('menu.update');
    Route::get('/messages', [CatererDashboardController::class, 'messages'])->name('messages');
    Route::get('/reviews', [CatererDashboardController::class, 'reviews'])->name('dashboard.reviews');
    Route::get('/earnings', [CatererDashboardController::class, 'earnings'])->name('earnings');
    Route::get('/profile-settings', [CatererDashboardController::class, 'profileSettings'])->name('profile.settings');
    Route::post('/profile-settings', [CatererDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Shared Message Routes (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{userId}', [MessageController::class, 'send'])->name('messages.send');
});
