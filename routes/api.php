<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;


// route for auth
Route::group([
    'middleware' => 'api', // set middleware api to list route in group    
    'prefix' => 'auth' // add prefix /auth e.g http://localhost:8000/api/auth/[route]
], function ($router) {
    //auth
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});


// route for expense
Route::group([
    'middleware' => 'api', // set middleware api to list route in group
    'prefix' => 'expense' // add prefix /expense e.g http://localhost:8000/api/expense/[route]
], function ($router) {
    Route::post('/', [ExpenseController::class, 'index'])->middleware('auth:api');
    Route::get('/show/{id}', [ExpenseController::class, 'show'])->middleware('auth:api');
    Route::post('/create', [ExpenseController::class, 'create'])->middleware('auth:api')->name('create');
    Route::put('/update/{id}', [ExpenseController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/delete/{id}', [ExpenseController::class, 'delete'])->middleware('auth:api')->name('delete');
});
