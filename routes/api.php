<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Settings\AccountController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\AccessRight\AccessRightsController;
use App\Http\Controllers\Api\ComingSoonMovie\ComingSoonMoviesController;
use App\Http\Controllers\Api\Exports\UserExportController;
use App\Http\Controllers\Api\Movie\AuthorsController;
use App\Http\Controllers\Api\Movie\CastsController;
use App\Http\Controllers\Api\Movie\DirectorsController;
use App\Http\Controllers\Api\Movie\GenresController;
use App\Http\Controllers\Api\Movie\MoviesController;
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
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');
    Route::get('/auth', [AuthController::class, 'show']);

    /**
     * * Access right
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
      * Author
      */
    Route::prefix('authors')->group(function () 
    {
        Route::get('/', [AuthorsController::class, 'index']);
        Route::get('/{author}', [AuthorsController::class, 'show']);
        Route::post('/', [AuthorsController::class, 'store']);
        Route::put('/{author}', [AuthorsController::class, 'update']);
        Route::delete('/', [AuthorsController::class, 'destroy']);
    });

    /**
      * Casts
      */
    Route::prefix('casts')->group(function () 
    {
        Route::get('/', [CastsController::class, 'index']);
        Route::get('/{cast}', [CastsController::class, 'show']);
        Route::post('/', [CastsController::class, 'store']);
        Route::put('/{cast}', [CastsController::class, 'update']);
        Route::delete('/', [CastsController::class, 'destroy']);
    });

    /**
     * Movie
     */
    Route::prefix('coming-soon-movies')->group(function () 
    {
        Route::get('/', [ComingSoonMoviesController::class, 'index']);
        Route::get('/{comingSoonMovie}', [ComingSoonMoviesController::class, 'show']);
        Route::post('/', [ComingSoonMoviesController::class, 'store']);
        Route::put('/{comingSoonMovie}', [ComingSoonMoviesController::class, 'update']);
        Route::delete('/', [ComingSoonMoviesController::class, 'destroy']);
    });

    /**
      * Director
      */
    Route::prefix('directors')->group(function () 
    {
        Route::get('/', [DirectorsController::class, 'index']);
        Route::get('/{director}', [DirectorsController::class, 'show']);
        Route::post('/', [DirectorsController::class, 'store']);
        Route::put('/{director}', [DirectorsController::class, 'update']);
        Route::delete('/', [DirectorsController::class, 'destroy']);
    });

    /**
      * Genre
      */
    Route::prefix('genres')->group(function () 
    {
        Route::get('/', [GenresController::class, 'index']);
        Route::get('/{genre}', [GenresController::class, 'show']);
        Route::post('/', [GenresController::class, 'store']);
        Route::put('/{genre}', [GenresController::class, 'update']);
        Route::delete('/', [GenresController::class, 'destroy']);
    });

    /**
     * Movie
     */
    Route::prefix('movies')->group(function () 
    {
        Route::get('/', [MoviesController::class, 'index']);
        Route::get('/{movie}', [MoviesController::class, 'show']);
        Route::post('/', [MoviesController::class, 'store']);
        Route::put('/{movie}', [MoviesController::class, 'update']);
        Route::delete('/', [MoviesController::class, 'destroy']);
    });

    /**
      * User Profile
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


