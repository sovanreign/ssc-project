<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Project routes
    Route::resource('projects', ProjectController::class);

    // Task routes
    Route::resource('tasks', TaskController::class);

    // Other routes
    Route::get('/leaderboard', function () {
        return view('leaderboard');
    })->name('leaderboard');

    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

    Route::get('/users', function () {
        return view('users.index');
    })->name('users.index');

    Route::get('/discussions', function () {
        return view('discussions.index');
    })->name('discussions.index');
});
