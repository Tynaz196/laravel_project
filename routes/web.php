<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\JobController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/hello', [PageController::class, 'hello'])->name('hello');


Route::get('/test-email', [JobController::class, 'processQueue'])->name('test.email');