<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\AchievementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [HomeController::class, 'index'])->middleware(['isActive']);
Route::get('/home', function () { return redirect('/'); });

Route::group([
    'middleware' => ['auth', 'verified']
    ], function () {
    Route::resource('/user', UsersController::class)->only(['show', 'isActive']);
    Route::resource('/students', StudentsController::class)->middleware(['isNotStudent', 'isActive']);
    Route::resource('/achievement', AchievementController::class)->middleware(['isActive']);
    Route::resource('/profile', ProfileController::class);
});
Route::get('/confirmation', [UsersController::class, 'confirmation'])->middleware(['auth', 'verified']);
Route::resource('/user', UsersController::class)->only(['store']);
Route::get('/achievement/{achievement_id}/delete', [AchievementController::class, 'delete'])->middleware(['auth', 'isActive']);

Route::group([
    'middleware' => ['auth', 'verified', 'isActive'],
    'prefix' => 'action'
    ], function () {
    Route::post('/user', [UsersController::class, 'action']);
    Route::post('/profile', [ProfileController::class, 'action']);
});

Route::group([
    'middleware' => ['auth'],
    'prefix' => 'email'
    ], function () {
    Route::get('/verify', function() { return view('auth.verify', ['noSidebar' => true, 'page_title' => "Ruang Siswa | Email verification"]); })->name('verification.notice');
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');;
    Route::post('/resend', [VerificationController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.resend');
    // Route::get('/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

Route::get('auth/google', [UsersController::class, 'google_login'])->name('google.login');
Route::get('auth/google/callback', [UsersController::class, 'google_callback'])->name('google.callback');
Route::post('auth/google/register', [UsersController::class, 'google_register']);
Route::post('/update_password', [UsersController::class, 'update_password']);

Route::get('/login', function(){ return redirect('/?login=true'); })->name('login');
Route::post('/login', [UsersController::class, 'authenticate']);
Route::get('/logout', [UsersController::class, 'logout']);

// Auth::routes(['verify'=>true]);