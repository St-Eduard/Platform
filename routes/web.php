<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ContestController as AdminContestController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Jury\DashboardController as JuryDashboardController;
use App\Http\Controllers\Jury\SubmissionController as JurySubmissionController;
use App\Http\Controllers\Participant\DashboardController as ParticipantDashboardController;
use App\Http\Controllers\Participant\SubmissionController as ParticipantSubmissionController;

// Публичные маршруты
Route::get('/', [ContestController::class, 'index'])->name('home');
Route::get('/contests', [ContestController::class, 'index'])->name('contests.index');
Route::get('/contests/{contest}', [ContestController::class, 'show'])->name('contests.show');

// Аутентификация (простые маршруты)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('contests', AdminContestController::class);
    Route::resource('users', AdminUserController::class);
});

// Jury
Route::prefix('jury')->name('jury.')->group(function () {
    Route::get('/dashboard', [JuryDashboardController::class, 'index'])->name('dashboard');
    Route::get('/submissions', [JurySubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [JurySubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/status', [JurySubmissionController::class, 'changeStatus'])->name('submissions.status');
    Route::post('/submissions/{submission}/comment', [JurySubmissionController::class, 'addComment'])->name('submissions.comment');
});

// Participant
Route::prefix('participant')->name('participant.')->group(function () {
    Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');
    Route::resource('submissions', ParticipantSubmissionController::class);
    Route::post('/submissions/{submission}/submit', [ParticipantSubmissionController::class, 'submit'])->name('submissions.submit');
    Route::post('/submissions/{submission}/comment', [ParticipantSubmissionController::class, 'addComment'])->name('submissions.comment');
});

// Attachments
Route::post('/submissions/{submission}/attachments', [AttachmentController::class, 'upload'])->name('attachments.upload');
Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

