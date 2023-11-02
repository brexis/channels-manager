<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function() {
    Route::resource('listings', App\Http\Controllers\ListingsController::class);
    Route::resource('listings.sources', App\Http\Controllers\SourcesController::class);
    Route::resource('listings.reservations', App\Http\Controllers\ReservationsController::class);
});

Route::get('/listings/{listing}/ical.ics', [App\Http\Controllers\ListingsController::class, 'ical'])->name('listings.ical');
