<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('/Login', [Controller::class, 'login'])->name('login');
Route::get('/Logout', [Controller::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/', [Controller::class, 'index'])->name('/');
    Route::get('/Directory', [Controller::class, 'directory'])->name('directory');
    Route::get('/getdirectory', [Controller::class, 'getDirectory'])->name('getdirectory');

    Route::get('/Users', [Controller::class, 'users'])->name('users');
    Route::get('/getusers', [Controller::class, 'getUsers'])->name('getusers');
});