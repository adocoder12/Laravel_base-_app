<?php

use App\Enums\RolesEnum;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // PUBLIC
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // PROTECTED
    Route::middleware('auth:sanctum')->group(function () {

        // PROFILE (ME)
        Route::group(['prefix' => 'me'], function () {
            Route::get('/', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/avatar', [ProfileController::class, 'updateAvatar']);
            Route::put('/password', [ProfileController::class, 'updatePassword']);
        });

        // PRODUCTS (Public viewing, Restricted editing)
        Route::apiResource('products', ProductController::class)
            ->only(['index', 'show']);

        Route::middleware('role:' . RolesEnum::Admin->value . '|' . RolesEnum::Staff->value)
            ->apiResource('products', ProductController::class)
            ->except(['index', 'show']);

        // ADMIN ONLY
        Route::middleware('role:' . RolesEnum::Admin->value)
            ->prefix('admin')
            ->group(function () {
                Route::get('/stats', fn() => response()->json(['data' => 'Stats']));
                Route::get('/users', [UserController::class, 'index']);
                Route::get('/users/{user}', [UserController::class, 'show']); // Added
                Route::put('/users/{user}', [UserController::class, 'update']);
                Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus']);
            });
    });
});
