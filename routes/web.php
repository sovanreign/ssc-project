<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Project routes
    Route::resource('projects', ProjectController::class);

    // Task routes
    Route::resource('tasks', TaskController::class);

    // User routes
    Route::resource('users', UserController::class);

    // Other routes
    Route::get('/leaderboard', function () {
        return view('leaderboard');
    })->name('leaderboard');

    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

    Route::get('/discussions', function () {
        return view('discussions.index');
    })->name('discussions.index');

    Route::post('/projects/{project}/complete', [ProjectController::class, 'markAsComplete'])->name('projects.complete');

    Route::post('/tasks/{task}/complete', [TaskController::class, 'markAsComplete'])->name('tasks.complete');
});
