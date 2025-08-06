<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\RatingController;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/search/ajax', [EventController::class, 'search'])->name('events.search');
Route::get('/events/featured', [EventController::class, 'featured'])->name('events.featured');
Route::get('/events/top-rated', [EventController::class, 'topRated'])->name('events.top-rated');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Booking routes (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/events/{event}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/pdf', [BookingController::class, 'downloadPDF'])->name('bookings.pdf');

    // Rating routes
    Route::post('/events/{event}/rate', [RatingController::class, 'store'])->name('ratings.store');
    Route::put('/ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update');
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
});

// QR Code verification (public)
Route::get('/tickets/verify/{qrCode}', [BookingController::class, 'verifyQR'])->name('tickets.verify');
Route::post('/tickets/use/{qrCode}', [BookingController::class, 'useTicket'])->name('tickets.use');

// Event ratings (public)
Route::get('/events/{event}/ratings', [RatingController::class, 'eventRatings'])->name('events.ratings');

// Organizer routes
Route::middleware(['auth'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');
    Route::get('/events', [OrganizerController::class, 'events'])->name('events');
    Route::get('/events/create', [OrganizerController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [OrganizerController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/edit', [OrganizerController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [OrganizerController::class, 'updateEvent'])->name('events.update');
    Route::post('/events/{event}/cancel', [OrganizerController::class, 'cancelEvent'])->name('events.cancel');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Event management
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::post('/events/{event}/approve', [AdminController::class, 'approveEvent'])->name('events.approve');
    Route::post('/events/{event}/reject', [AdminController::class, 'rejectEvent'])->name('events.reject');
    Route::post('/events/{event}/toggle-featured', [AdminController::class, 'toggleFeatured'])->name('events.toggle-featured');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/role', [AdminController::class, 'changeUserRole'])->name('users.role');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');

    // Booking management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');

    // Analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

    // Category management
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
});