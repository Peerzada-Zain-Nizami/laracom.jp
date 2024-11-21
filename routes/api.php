<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Email verification routes
Route::post('/email/verify', [AuthController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify')->middleware('auth:sanctum');

// Password reset routes
Route::post('/password/email', [AuthController::class, 'sendPasswordResetLink']);
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::prefix('content')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('tutorials', TutorialController::class);
    Route::apiResource('references', ReferenceController::class);
    // Route::apiResource('blogs', BlogController::class);

    // // Fetch categories and subtopics
    // Route::get('categories', [CategoryController::class, 'index']);
    // Route::get('categories/{category}/subtopics', [CategoryController::class, 'subtopics']);

    // // Fetch user-specific content
    // Route::get('user/tutorials', [TutorialController::class, 'userTutorials']);
    // Route::get('user/blogs', [BlogController::class, 'userBlogs']);
});

