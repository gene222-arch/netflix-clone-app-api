<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Settings\AccountController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\AccessRight\AccessRightsController;
use App\Http\Controllers\Api\Dashboards\DashboardsController;
use App\Http\Controllers\Api\Movie\ComingSoonMoviesController;
use App\Http\Controllers\Api\Exports\UserExportController;
use App\Http\Controllers\Api\Movie\AuthorsController;
use App\Http\Controllers\Api\Movie\CastsController;
use App\Http\Controllers\Api\Movie\DirectorsController;
use App\Http\Controllers\Api\Movie\GenresController;
use App\Http\Controllers\Api\Movie\MoviesController;
use App\Http\Controllers\Api\Movie\MyListsController;
use App\Http\Controllers\Api\Movie\RecentlyWatchedMoviesController;
use App\Http\Controllers\Api\Movie\RemindMesController;
use App\Http\Controllers\Api\Movie\UserRatingsController;
use App\Http\Controllers\Api\User\UserProfilesController;

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
        Route::put('/{author}/enabled', [AuthorsController::class, 'updateEnabledStatus']);
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
        Route::put('/{cast}/enabled', [CastsController::class, 'updateEnabledStatus']);
        Route::delete('/', [CastsController::class, 'destroy']);
    });

    /**
     * Coming Soon Movie
     */
    Route::prefix('coming-soon-movies')->group(function () 
    {
        Route::get('/', [ComingSoonMoviesController::class, 'index']);
        Route::get('/{comingSoonMovie}', [ComingSoonMoviesController::class, 'show']);
        Route::post('/', [ComingSoonMoviesController::class, 'store']);
        Route::post('/upload/poster', [ComingSoonMoviesController::class, 'uploadPoster']);
        Route::post('/upload/wallpaper', [ComingSoonMoviesController::class, 'uploadWallpaper']);
        Route::post('/upload/title-logo', [ComingSoonMoviesController::class, 'uploadTitleLogo']);
        Route::post('/upload/video-trailer', [ComingSoonMoviesController::class, 'uploadVideo']);
        Route::put('/{comingSoonMovie}', [ComingSoonMoviesController::class, 'update']);
        Route::put('/{comingSoonMovie}/status', [ComingSoonMoviesController::class, 'updateStatus']);
        Route::delete('/', [ComingSoonMoviesController::class, 'destroy']);

        Route::get('/{comingSoonMovie}/trailers/{trailer}', [ComingSoonMoviesController::class, 'showTrailer']);
        Route::post('/{comingSoonMovie}/trailers', [ComingSoonMoviesController::class, 'storeTrailer']);
        Route::post('/{comingSoonMovie}/trailers/upload/poster', [ComingSoonMoviesController::class, 'uploadTrailerPoster']);
        Route::post('/{comingSoonMovie}/trailers/upload/wallpaper', [ComingSoonMoviesController::class, 'uploadTrailerWallpaper']);
        Route::post('/{comingSoonMovie}/trailers/upload/title-logo', [ComingSoonMoviesController::class, 'uploadTrailerTitleLogo']);
        Route::post('/{comingSoonMovie}/trailers/upload/video', [ComingSoonMoviesController::class, 'uploadTrailerVideo']);
        Route::put('/{comingSoonMovie}/trailers/{trailer}/update', [ComingSoonMoviesController::class, 'updateTrailer']);
        Route::delete('/{comingSoonMovie}/trailers', [ComingSoonMoviesController::class, 'destroyTrailer']);
    });

	Route::get('/dashboard', DashboardsController::class);

    /**
      * Director
      */
    Route::prefix('directors')->group(function () 
    {
        Route::get('/', [DirectorsController::class, 'index']);
        Route::get('/{director}', [DirectorsController::class, 'show']);
        Route::post('/', [DirectorsController::class, 'store']);
        Route::put('/{director}', [DirectorsController::class, 'update']);
        Route::put('/{director}/enabled', [DirectorsController::class, 'updateEnabledStatus']);
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
        Route::put('/{genre}/enabled', [GenresController::class, 'updateEnabledStatus']);
        Route::delete('/', [GenresController::class, 'destroy']);
    });

    /**
     * Movie
     */
    Route::prefix('movies')->group(function () 
    {
        Route::get('/', [MoviesController::class, 'index']);
        Route::get('/categorized', [MoviesController::class, 'categorizedMovies']);
        Route::get('/top-searches', [MoviesController::class, 'topSearches']);
        Route::get('/latest/20', [MoviesController::class, 'getLatestTwenty']);
        Route::get('/{movie}', [MoviesController::class, 'show']);
        Route::post('/', [MoviesController::class, 'store']);
        Route::post('/upload/poster', [MoviesController::class, 'uploadPoster']);
        Route::post('/upload/wallpaper', [MoviesController::class, 'uploadWallpaper']);
        Route::post('/upload/title-logo', [MoviesController::class, 'uploadTitleLogo']);
        Route::post('/upload/video', [MoviesController::class, 'uploadVideo']);
        Route::put('/{movie}', [MoviesController::class, 'update']);
        Route::match(['post', 'put'], '/{movie}/views', [MoviesController::class, 'incrementViews']);
        Route::match(['post', 'put'], '/{movie}/search-count', [MoviesController::class, 'incrementSearchCount']);
        Route::delete('/', [MoviesController::class, 'destroy']);
    });

    Route::post('my-lists', [MyListsController::class, 'toggle']);
    Route::post('remind-mes', [RemindMesController::class, 'toggle']);

    /**
      * Recently Watched Movie
      */
    Route::prefix('recently-watched-movies')->group(function () 
    {
        Route::get('/', [RecentlyWatchedMoviesController::class, 'index']);
        Route::get('/{recentlyWatchedMovie}', [RecentlyWatchedMoviesController::class, 'show']);
        Route::post('/user-profiles/{userProfile}', [RecentlyWatchedMoviesController::class, 'store']);
        Route::put('/', [RecentlyWatchedMoviesController::class, 'update']);
        Route::delete('/', [RecentlyWatchedMoviesController::class, 'destroy']);
        Route::delete('/clear', [RecentlyWatchedMoviesController::class, 'destroy']);
    });

    /**
      * User Profile
      */
    Route::prefix('user-profiles')->group(function () 
    {
        Route::get('/', [UserProfilesController::class, 'index']);
        Route::get('/{profile}', [UserProfilesController::class, 'show']);
        Route::post('/', [UserProfilesController::class, 'store']);
        Route::post('/avatar-upload', [UserProfilesController::class, 'uploadAvatar']);
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

    /**
      * User Rating
      */
    Route::prefix('user-ratings')->group(function () 
    {
        Route::get('/', [UserRatingsController::class, 'index']);
        Route::get('/users', [UserRatingsController::class, 'showByUserID']);
        Route::get('/user-profiles/{userProfile}', [UserRatingsController::class, 'showByUserProfileID']);
        Route::get('/movies/{movie}', [UserRatingsController::class, 'showByMovieID']);
        Route::get('/{userRating}', [UserRatingsController::class, 'show']);
        Route::post('/', [UserRatingsController::class, 'store']);
        Route::delete('/', [UserRatingsController::class, 'destroy']);
    });
});


