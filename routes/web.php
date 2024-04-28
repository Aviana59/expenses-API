<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', [AuthController::class, 'index'])->withoutMiddleware('isLogin');
// Route::get('/home', [ExpenseController::class, 'index']);

Route::get('/', function () {
    return view('login');
});
Route::get('/home', function () {
    return view('expense.home');
})->name('home');
Route::get('/add', function () {
    return view('expense.add');
})->name('add-expanse');
Route::get('/register', function () {
    return view('register');
})->name('register');
