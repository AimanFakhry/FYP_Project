<?php

use Illuminate\Support\Facades\Route;

// Public & User Controllers
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LearningPathController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\DailyChallengeController; // <-- IMPORTED

// ... (Other imports remain the same) ...
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LessonGroupController as AdminLessonGroupController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\AchievementController as AdminAchievementController;
use App\Http\Middleware\IsAdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ... (Public & Guest Routes remain the same) ...
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/reset-password', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});


// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('users.dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/reset-course', [ProfileController::class, 'resetCourse'])->name('profile.reset_course');

    // --- DAILY CHALLENGE ROUTES ---
    Route::get('/daily', [DailyChallengeController::class, 'index'])->name('daily.index');
    Route::post('/daily/check', [DailyChallengeController::class, 'check'])->name('daily.check');

    // Course & Lesson Routes
    Route::get('/courses', [LearningPathController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [LearningPathController::class, 'show'])->name('courses.show');
    
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])
         ->name('lessons.show')
         ->scopeBindings();

    Route::post('/lesson/run-code', [LessonController::class, 'runCode'])->name('lessons.run');
    Route::post('/lesson/complete', [LessonController::class, 'complete'])->name('lessons.complete');
    
    //DOCKER CHECK ROUTE
    Route::get('/lesson/check-docker', [LessonController::class, 'checkDocker'])->name('lessons.check_docker');

    // Ranking
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');

});


// Admin Routes
Route::middleware(['auth', IsAdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/profile', [ProfileController::class, 'showAdmin'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'updateAdmin'])->name('profile.update');
    
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [AdminCourseController::class, 'show'])->name('courses.show');
    
    // Groups & Lessons Management
    Route::get('/courses/{course}/groups/create', [AdminLessonGroupController::class, 'create'])->name('groups.create');
    Route::post('/courses/{course}/groups', [AdminLessonGroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}/edit', [AdminLessonGroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{group}', [AdminLessonGroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [AdminLessonGroupController::class, 'destroy'])->name('groups.destroy');
    
    Route::get('/groups/{group}/lessons/create', [AdminLessonController::class, 'create'])->name('lessons.create');
    Route::post('/groups/{group}/lessons', [AdminLessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('lessons.destroy');
    
    Route::resource('achievements', AdminAchievementController::class);
});