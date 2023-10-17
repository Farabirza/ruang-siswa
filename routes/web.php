<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\SteamProjectController;
use App\Http\Controllers\SteamCategoryController;
use App\Http\Controllers\SteamLogBookController;

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

// 40620b4f-cc40-402a-8b7d-b6e48f736031

Route::get('/', [HomeController::class, 'index'])->middleware(['isActive']);
Route::get('/home', function () { return redirect('/'); });

// Authentication
Route::group([
    'middleware' => ['auth', 'verified', 'isActive']
    ], function () {
    Route::resource('/students', StudentsController::class)->except(['index'])->middleware(['isNotStudent']);
    Route::resource('/students', StudentsController::class)->only(['index']);
    Route::resource('/achievement', AchievementController::class)->except(['show', 'index']);
    Route::resource('/steamProject', SteamProjectController::class)->except(['show', 'index']);
    Route::resource('/steamCategory', SteamCategoryController::class);
    Route::resource('/steamLogBook', SteamLogBookController::class)->only(['edit', 'update']);
    Route::get('/user/{user_id}/confirm', [UsersController::class, 'confirm_user']);
    Route::get('/user/{user_id}/suspend', [UsersController::class, 'suspend_user']);
    Route::get('/comment/{comment_id}/delete', [CommentController::class, 'delete']);
});
Route::resource('/user', UsersController::class)->only(['show'])->middleware(['auth', 'verified']);
Route::resource('/user', UsersController::class)->only(['store']);
Route::get('/confirmation', [UsersController::class, 'confirmation'])->middleware(['auth', 'verified']);
Route::resource('/profile', ProfileController::class)->except(['show'])->middleware(['auth', 'verified']);
Route::resource('/profile', ProfileController::class)->only(['show']);

// Image
Route::get('/image/{image_id}/delete', [ImageController::class, 'delete'])->middleware(['auth', 'verified']);

// STEAM Project
Route::group([
    'middleware' => ['auth', 'verified', 'isActive'],
    'prefix' => 'steamProject'
    ], function () {
    Route::get('/{steam_id}/delete', [SteamProjectController::class, 'destroy']);
    Route::get('/{steam_id}/logbook/create', [SteamLogBookController::class, 'create']);
    Route::post('/{steam_id}/comment', [SteamProjectController::class, 'store_comment']);
});
Route::resource('/steamProject', SteamProjectController::class)->only(['show', 'index']);
Route::resource('/steamLogBook', SteamLogBookController::class)->only(['show']);

// Achievement
Route::group([
    'middleware' => ['auth', 'verified', 'isActive'],
    'prefix' => 'achievement'
    ], function () {
    Route::get('/{achievement_id}/delete', [AchievementController::class, 'delete']);
    Route::get('/{achievement_id}/remove/image', [AchievementController::class, 'remove_image']);
    Route::get('/{achievement_id}/remove/pdf', [AchievementController::class, 'remove_pdf']);
    Route::get('/{achievement_id}/confirm', [AchievementController::class, 'confirm'])->middleware('isNotStudent');
    Route::post('/{achievement_id}/comment', [AchievementController::class, 'store_comment']);
});
Route::resource('/achievement', AchievementController::class)->only(['show', 'index']);

// Admin
Route::group([
    'middleware' => ['auth', 'verified', 'isAdmin'],
    'prefix' => 'admin'
    ], function () {
    Route::get('/user', [AdminController::class, 'user_controller']);
});

// Action
Route::group([
    'middleware' => ['auth', 'verified'],
    'prefix' => 'action'
    ], function () {
    Route::post('/admin', [AdminController::class, 'action'])->middleware(['isAdmin']);
    Route::post('/user', [UsersController::class, 'action']);
    Route::post('/student', [StudentsController::class, 'action']);
    Route::post('/profile', [ProfileController::class, 'action']);
    Route::post('/achievement', [AchievementController::class, 'action']);
    Route::post('/steam', [SteamProjectController::class, 'action']);
    Route::post('/logbook', [SteamLogBookController::class, 'action']);
});

// Email 
Route::group([
    'middleware' => ['auth'],
    'prefix' => 'email'
    ], function () {
    Route::get('/verify', function() { return view('auth.verify', ['noSidebar' => true, 'page_title' => "Ruang Siswa | Email verification"]); })->name('verification.notice');
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');;
    Route::post('/resend', [VerificationController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.resend');
});

// Google 
Route::get('auth/google', [UsersController::class, 'google_login'])->name('google.login');
Route::get('auth/google/callback', [UsersController::class, 'google_callback'])->name('google.callback');
Route::post('auth/google/register', [UsersController::class, 'google_register']);
Route::post('/update_password', [UsersController::class, 'update_password']);

Route::get('/login', function(){ return redirect('/?login=true'); })->name('login');
Route::post('/login', [UsersController::class, 'authenticate']);
Route::get('/logout', [UsersController::class, 'logout']);

// Auth::routes(['verify'=>true]);