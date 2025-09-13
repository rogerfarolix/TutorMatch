<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('tutors', TutorController::class);
Route::resource('students', StudentController::class);
Route::get('students/{student}/matches', [StudentController::class, 'matches'])->name('students.matches');

// Routes pour les matchs
Route::get('matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('matches/{match}', [MatchController::class, 'show'])->name('matches.show');
Route::post('matches/generate-all', [MatchController::class, 'generateAll'])->name('matches.generate-all');
Route::patch('matches/{match}/accept', [MatchController::class, 'accept'])->name('matches.accept');
Route::patch('matches/{match}/reject', [MatchController::class, 'reject'])->name('matches.reject');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
