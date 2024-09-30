<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', function () {
    return redirect('/');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/user', [AuthController::class, 'user']);

Route::get('/dashboard', function() {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');

Route::get('/book-user/statuses', [BookUserController::class, 'statuses'])->name('bookUser.statuses');
Route::post('/book-user', [BookUserController::class, 'store'])
    ->middleware('auth')
    ->name('book-user.store');
