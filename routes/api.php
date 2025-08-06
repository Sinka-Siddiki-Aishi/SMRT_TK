<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryController;

// Example: Get all events (API)
Route::get('/events', [EventController::class, 'apiIndex']);
Route::get('/categories', [CategoryController::class, 'apiIndex']);