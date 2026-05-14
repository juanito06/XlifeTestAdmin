<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SecurityController;
use App\Http\Controllers\Api\StatsController;

// Usuarios
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/posts', [UserController::class, 'posts']);
Route::get('/users/{id}/activity', [UserController::class, 'activity']);

// Contenido
Route::get('/posts', [PostController::class, 'index']);
Route::get('/comments', [CommentController::class, 'index']);

// Reportes
Route::get('/reports', [ReportController::class, 'index']);
Route::get('/reports/summary', [ReportController::class, 'summary']);

// Seguridad
Route::get('/security/logs', [SecurityController::class, 'logs']);
Route::get('/security/blocked-ips', [SecurityController::class, 'blockedIps']);
Route::get('/security/blocked-phones', [SecurityController::class, 'blockedPhones']);
Route::get('/security/settings', [SecurityController::class, 'settings']);

// Estadísticas
Route::get('/stats/overview', [StatsController::class, 'overview']);
Route::get('/stats/user-growth', [StatsController::class, 'userGrowth']);
Route::get('/stats/geographic', [StatsController::class, 'geographic']);
Route::get('/stats/viral-posts', [StatsController::class, 'viralPosts']);