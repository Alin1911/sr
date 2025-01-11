<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TransactionController;

Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::any('/event/search', [EventController::class, 'search'])->name('search');
Route::get('/event/{id}', [EventController::class, 'show'])->name('search');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/buy/{id}', [TransactionController::class, 'buy'])->name('buy');
Route::post('/cart/{id}', [TransactionController::class, 'cart'])->name('cart');


