<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPostController;

// Post Controllers
use App\Http\Controllers\Post\PostListController;
use App\Http\Controllers\Post\PostCreateController;
use App\Http\Controllers\Post\PostViewController;
use App\Http\Controllers\Post\PostUpdateController;
use App\Http\Controllers\Post\PostDeleteController;


Route::redirect('/', '/news');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmailJob'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Public route - accessible to everyone
Route::get('/news', [HomeController::class, 'index'])->name('home');
Route::get('/post/{post:slug}', [PublicPostController::class, 'show'])->name('public.post.show');

Route::middleware(['auth', 'check.user.status'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Post List Routes
    Route::get('/posts', [PostListController::class, 'index'])->name('posts.index');
    Route::get('/posts/data', [PostListController::class, 'data'])->name('posts.data');

    // Post Create Routes
    Route::get('/posts/create', [PostCreateController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostCreateController::class, 'store'])->name('posts.store');

    // Post View Routes
    Route::get('/posts/{post:slug}', [PostViewController::class, 'show'])->name('posts.show');

    // Post Update Routes
    Route::get('/posts/{post}/edit', [PostUpdateController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostUpdateController::class, 'update'])->name('posts.update');

    // Post Delete Routes
    Route::delete('/posts/{post}', [PostDeleteController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/posts/destroy-all', [PostDeleteController::class, 'destroyAll'])->name('posts.destroyAll');
});
