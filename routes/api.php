<?php

use App\Http\Controllers\API\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\CodeExecutionController;
use App\Http\Controllers\SearchController;
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
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}/subtopics', [CategoryController::class, 'subtopics']);

    Route::apiResource('blog-posts', BlogPostController::class); // Blog post CRUD
    Route::get('user/blog-posts', [BlogPostController::class, 'userBlogPosts']);

    // // Fetch user-specific content
    Route::get('user/tutorials', [TutorialController::class, 'userTutorials']);
    // Route::get('user/blogs', [BlogController::class, 'userBlogs']);
});

    Route::middleware('auth:sanctum')->group(function () {
    Route::get('quizzes', [QuizController::class, 'index']);
    Route::get('quizzes/{id}', [QuizController::class, 'show']);
    Route::post('quizzes/evaluate/{id}', [QuizController::class, 'evaluate']);

    Route::post('questions', [QuestionController::class, 'store']);
    Route::put('questions/{id}', [QuestionController::class, 'update']);
    Route::delete('questions/{id}', [QuestionController::class, 'destroy']);

    Route::get('comments', [CommentController::class, 'index']);
    Route::post('comments', [CommentController::class, 'store']);
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);

    Route::post('likes', [LikeController::class, 'store']);
    Route::delete('likes', [LikeController::class, 'destroy']);

    Route::get('/notifications', [NotificationController::class, 'index']); // Get all notifications
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']); // Mark notifications as read
    Route::post('/notifications/send', [NotificationController::class, 'sendNotification']);
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    });

Route::post('/execute-code', [CodeExecutionController::class, 'execute'])->name('execute.code');


