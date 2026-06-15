<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = Illuminate\Support\Facades\Auth::user();
    return Inertia::render('Dashboard', [
        'tasks_proses' => $user->tasks()->where('status', 'Proses')->count(),
        'tasks_selesai' => $user->tasks()->where('status', 'Selesai')->count(),
        'recent_tasks' => $user->tasks()->latest()->take(3)->get()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    Route::get('/calendar', function () {
        return Inertia::render('Calendar');
    })->name('calendar');
});

require __DIR__.'/auth.php';
