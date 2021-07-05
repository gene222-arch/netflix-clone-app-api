<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Settings\AccountController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\AccessRight\AccessRightsController;
use App\Http\Controllers\Api\Exports\UserExportController;
use App\Http\Controllers\UserProfilesController;

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


/**
 * Auth Module
 */
Route::middleware(['api'])->group(function () 
{

    /**
     * * Login
     * * Register
     */
    Route::prefix('auth')->group(function () 
    {
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/register', [RegisterController::class, 'register']);
    });

    /**
     * * Reset Password
     */
    Route::prefix('forgot-password')->group(function () 
    {
        Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('/reset', [ResetPasswordController::class, 'reset']);
    });

    /**
     * * Logout
     */
    Route::middleware(['auth:api'])->group(function () 
    {
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/auth', [AuthController::class, 'show']);
    });

    /**
     * * Access rights
     */
    Route::prefix('access-rights')->group(function () 
    {
        Route::get('/', [AccessRightsController::class, 'index']);
        Route::get('/{id}', [AccessRightsController::class, 'show']);
        Route::post('/', [AccessRightsController::class, 'store']);
        Route::put('/', [AccessRightsController::class, 'update']);
        Route::delete('/', [AccessRightsController::class, 'destroy']);
    });
    
    /**
      * Route
      */
    Route::prefix('user-profiles')->group(function () 
    {
        Route::get('/', [UserProfilesController::class, 'index']);
        Route::get('/{profile}', [UserProfilesController::class, 'show']);
        Route::post('/', [UserProfilesController::class, 'store']);
        Route::put('/{profile}', [UserProfilesController::class, 'update']);
        Route::delete('/{profile}', [UserProfilesController::class, 'destroy']);
    });

    /**
     * * Settings
     */
    Route::prefix('settings')->group(function () 
    {
        Route::prefix('account')->group(function () 
        {
            Route::post('/verify', [AccountController::class, 'verify']);
            Route::put('/', [AccountController::class, 'update']);
        });
    });
});


