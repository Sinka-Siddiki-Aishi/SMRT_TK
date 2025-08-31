<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;

use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\RecommendationController;

// Home - Always redirect to login (users must sign in for access)
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Organizer Dashboard


// User Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/user/wallet', [UserController::class, 'wallet'])->name('user.wallet');
    Route::post('/user/wallet/recharge', [UserController::class, 'recharge'])->name('user.wallet.recharge');
    Route::get('/booking-history', [UserController::class, 'bookingHistory'])->name('user.booking-history');
});

// Analytics
Route::middleware(['auth'])->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    Route::get('/analytics/event/{event}', [AnalyticsController::class, 'dashboard'])->name('analytics.event');
});

// Recommendations
Route::get('/recommendations', [RecommendationController::class, 'getRecommendations'])->name('recommendations.index');
Route::get('/api/recommendations', [RecommendationController::class, 'api'])->name('api.recommendations');

// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/search/ajax', [EventController::class, 'search'])->name('events.search');
Route::get('/events/featured', [EventController::class, 'featured'])->name('events.featured');
Route::get('/events/top-rated', [EventController::class, 'topRated'])->name('events.top-rated');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Booking routes (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::get('/bookings/create/{event}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/{event}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/pdf', [BookingController::class, 'downloadPDF'])->name('bookings.pdf');
    Route::get('/bookings/{booking}/stream', [BookingController::class, 'streamPDF'])->name('bookings.stream');

    // Ticket Verification
    Route::get('/tickets/verify/{qr_code}', [BookingController::class, 'verifyQR'])->name('tickets.verify');

    // Profile routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // Rating routes
    Route::post('/events/{event}/rate', [RatingController::class, 'store'])->name('ratings.store');
    Route::put('/ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update');
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
});

// QR Code verification (public)
Route::post('/tickets/use/{qrCode}', [BookingController::class, 'useTicket'])->name('tickets.use');

// Event ratings (public)
Route::get('/events/{event}/ratings', [RatingController::class, 'eventRatings'])->name('events.ratings');

// Organizer routes
Route::middleware(['auth', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');
    Route::get('/events', [OrganizerController::class, 'events'])->name('events');
    Route::get('/events/create', [OrganizerController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [OrganizerController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/edit', [OrganizerController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [OrganizerController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [OrganizerController::class, 'deleteEvent'])->name('events.delete');
});