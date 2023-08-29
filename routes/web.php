<?php

use Illuminate\Support\Facades\Route;
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
Route::get('/', [HomeController::class, 'index']);
Route::get('/home', function () { return redirect('/'); });

Route::group([
    'middleware' => ['auth']
    ], function () {
    Route::resource('/user', UsersController::class)->only(['show']);
    Route::resource('/students', StudentsController::class)->middleware(['isNotStudent']);
    Route::resource('/achievement', AchievementController::class);
    Route::resource('/profile', ProfileController::class);
});
Route::resource('/user', UsersController::class)->only(['store']);
Route::get('/achievement/{achievement_id}/delete', [AchievementController::class, 'delete']);

Route::group([
    'middleware' => ['auth'],
    'prefix' => 'action'
    ], function () {
    Route::post('/user', [UsersController::class, 'action']);
    Route::post('/profile', [ProfileController::class, 'action']);
});

Route::get('auth/google', [UsersController::class, 'google_login'])->name('google.login');
Route::get('auth/google/callback', [UsersController::class, 'google_callback'])->name('google.callback');
Route::post('auth/google/register', [UsersController::class, 'google_register']);
Route::post('/update_password', [UsersController::class, 'update_password']);

Route::post('/', [UsersController::class, 'store']);
Route::post('/login', [UsersController::class, 'authenticate']);
Route::get('/logout', [UsersController::class, 'logout']);

// Auth::routes(['verify'=>true]);