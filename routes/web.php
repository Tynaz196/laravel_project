<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPostController;
use App\Http\Controllers\PostController;


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

    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/data', [PostController::class, 'data'])->name('posts.data');
    Route::delete('posts/destroy-all', [PostController::class, 'destroyAll'])->name('posts.destroyAll');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});

// Admin routes - protected by admin middleware
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin Posts Management
    Route::get('posts/data', [App\Http\Controllers\Admin\AdminPostController::class, 'data'])->name('posts.data');
    Route::get('posts', [App\Http\Controllers\Admin\AdminPostController::class, 'index'])->name('posts.index');
    Route::delete('posts/destroy-all', [App\Http\Controllers\Admin\AdminPostController::class, 'destroyAll'])->name('posts.destroyAll');
    Route::delete('/posts/{post}', [App\Http\Controllers\Admin\AdminPostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{post:slug}', [App\Http\Controllers\Admin\AdminPostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [App\Http\Controllers\Admin\AdminPostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [App\Http\Controllers\Admin\AdminPostController::class, 'update'])->name('posts.update');

    // Admin dashboard redirect
    Route::get('/', function () {
        return to_route('admin.posts.index');
    });
});
