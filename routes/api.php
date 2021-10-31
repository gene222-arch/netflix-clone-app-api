<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Settings\AccountController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\AccessRight\AccessRightsController;
use App\Http\Controllers\Api\ActivityLogsController;
use App\Http\Controllers\Api\Auth\UploadUserAvatarController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Dashboards\DashboardsController;
use App\Http\Controllers\Api\EmployeesController;
use App\Http\Controllers\Api\Movie\ComingSoonMoviesController;
use App\Http\Controllers\Api\Exports\UserExportController;
use App\Http\Controllers\Api\Movie\AuthorsController;
use App\Http\Controllers\Api\Movie\CastsController;
use App\Http\Controllers\Api\Movie\DirectorsController;
use App\Http\Controllers\Api\Movie\GenresController;
use App\Http\Controllers\Api\Movie\MovieNotificationsController;
use App\Http\Controllers\Api\Movie\MoviesController;
use App\Http\Controllers\Api\Movie\MyDownloadsController;
use App\Http\Controllers\Api\Movie\MyListsController;
use App\Http\Controllers\Api\Movie\RecentlyWatchedMoviesController;
use App\Http\Controllers\Api\Movie\ReleasedMovieNotifiedUsersController;
use App\Http\Controllers\Api\Movie\RemindMesController;
use App\Http\Controllers\Api\Movie\UserRatingsController;
use App\Http\Controllers\Api\PaymentMethodsController;
use App\Http\Controllers\Api\SubscriptionsController;
use App\Http\Controllers\Api\User\UserProfilesController;
use App\Http\Controllers\Api\User\UsersController;

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

Route::middleware(['api', 'verified'])->group(function () 
{
    /**
     * * Login
     * * Register
     */
    Route::prefix('auth')->group(function () 
    {
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/register', [RegisterController::class, 'register'])->withoutMiddleware('verified');
        Route::post('/upload-avatar', [UploadUserAvatarController::class, 'uploadAvatar'])
            ->withoutMiddleware('verified');
            
        Route::middleware(['auth:api'])->group(function () {
            Route::get('/', [AuthController::class, 'show']);
            Route::post('/check-password', [AuthController::class, 'checkPassword']);
        });
    });

    /**
     * * Reset Password
     */
    Route::prefix('forgot-password')->group(function () 
    {
        Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('/reset', [ResetPasswordController::class, 'reset'])->withoutMiddleware('verified');
    });

    Route::prefix('email')->group(function () 
    {
        Route::get('/verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
            ->name('verification.verify')->withoutMiddleware('verified');

        Route::get('/resend', [VerificationController::class, 'resend'])
            ->name('verification.resend')->withoutMiddleware('verified');
    });

    /**
     * * Logout
     */
    Route::middleware('auth:api')->group(function () 
    {
        Route::post('/logout', [LoginController::class, 'logout'])->withoutMiddleware('verified');
    });

    /**
     * * Access right
     */
    Route::prefix('access-rights')->group(function () 
    {
        Route::get('/permissions', [AccessRightsController::class, 'permissions']);
        Route::get('/', [AccessRightsController::class, 'index']);
        Route::get('/{role}', [AccessRightsController::class, 'show']);
        Route::post('/{role}/assign', [AccessRightsController::class, 'assign']);
        Route::post('/', [AccessRightsController::class, 'store']);
        Route::put('/{role}', [AccessRightsController::class, 'update']);
        Route::delete('/', [AccessRightsController::class, 'destroy']);
    });
    
    /**
     * * Activity Log
     */
    Route::prefix('activity-logs')->group(function () 
    {
        Route::get('/', [ActivityLogsController::class, 'index']);
        Route::get('/{activityLog}', [ActivityLogsController::class, 'show']);
        Route::post('/', [ActivityLogsController::class, 'store']);
        Route::put('/{activityLog}', [ActivityLogsController::class, 'update']);
        Route::delete('/', [ActivityLogsController::class, 'destroy']);
    });


    /**
      * Author
      */
    Route::prefix('authors')->group(function () 
    {
        Route::get('/', [AuthorsController::class, 'index']);
        Route::get('/{author}', [AuthorsController::class, 'show']);
        Route::post('/', [AuthorsController::class, 'store']);
        Route::post('/upload-avatar', [AuthorsController::class, 'uploadAvatar']);
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
        Route::post('/upload-avatar', [CastsController::class, 'uploadAvatar']);
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
        Route::put('/{comingSoonMovie}/release', [ComingSoonMoviesController::class, 'release']);
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
        Route::post('/upload-avatar', [DirectorsController::class, 'uploadAvatar']);
        Route::put('/{director}', [DirectorsController::class, 'update']);
        Route::put('/{director}/enabled', [DirectorsController::class, 'updateEnabledStatus']);
        Route::delete('/', [DirectorsController::class, 'destroy']);
    });

    /**
     * * Employee
     */
    Route::group([
        'prefix' => 'employees',
        'middleware' => ['auth:api']
    ], function () 
    {
        Route::get('/', [EmployeesController::class, 'index']);
        Route::get('/{employee}', [EmployeesController::class, 'show']);
        Route::post('/', [EmployeesController::class, 'store']);
        Route::post('/avatar', [EmployeesController::class, 'uploadAvatar']);
        Route::put('/verify/email', [EmployeesController::class, 'verify'])->withoutMiddleware(['verified', 'auth:api']);
        Route::put('/{employee}', [EmployeesController::class, 'update']);
        Route::delete('/', [EmployeesController::class, 'destroy']);
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
        Route::get('/most-liked', [MoviesController::class, 'mostLikedMovies']);
        Route::get('/top-searches', [MoviesController::class, 'topSearches']);
        Route::get('/latest/20', [MoviesController::class, 'getLatestTwenty']);
        Route::get('/random', [MoviesController::class, 'showRandom']);
        Route::get('/{movie}', [MoviesController::class, 'show']);
        Route::post('/', [MoviesController::class, 'store']);
        Route::post('/upload/poster', [MoviesController::class, 'uploadPoster']);
        Route::post('/upload/wallpaper', [MoviesController::class, 'uploadWallpaper']);
        Route::post('/upload/title-logo', [MoviesController::class, 'uploadTitleLogo']);
        Route::post('/upload/video', [MoviesController::class, 'uploadVideo']);
        Route::post('/upload/video-preview', [MoviesController::class, 'uploadVideoPreview']);
        Route::put('/{movie}', [MoviesController::class, 'update']);
        Route::match(['post', 'put'], '/{movie}/views', [MoviesController::class, 'incrementViews']);
        Route::match(['post', 'put'], '/{movie}/search-count', [MoviesController::class, 'incrementSearchCount']);
        Route::delete('/', [MoviesController::class, 'destroy']);
    });

    Route::get('movie-notifications', [MovieNotificationsController::class, 'index'])->middleware('auth:api');
    Route::post('my-lists', [MyListsController::class, 'toggle']);
    
    /**
      * Remind me
      */
    Route::prefix('remind-mes')->group(function () 
    {
        Route::post('/', [RemindMesController::class, 'toggle']);
        Route::put('/mark-as-read', [RemindMesController::class, 'markAsRead']);
    });

    /**
      * Released movie notified users
      */
      Route::prefix('released-movie-notified-users')->group(function () 
      {
          Route::post('/{releasedMovie}', [ReleasedMovieNotifiedUsersController::class, 'store']);
      });


    /**
      * My Downloads
      */
      Route::prefix('my-downloads')->group(function () 
      {
          Route::get('/', [MyDownloadsController::class, 'index']);
          Route::get('/{myDownload}', [MyDownloadsController::class, 'show']);
          Route::post('/', [MyDownloadsController::class, 'store']);
          Route::delete('/user-profiles/{userProfileId}', [MyDownloadsController::class, 'destroy']);
      });


    /**
      * Payment Method
      */
    Route::group([ 
        'prefix' => 'payment-methods'
    ], function () 
    {
        Route::post('/e-payment', [PaymentMethodsController::class, 'ePayment'])
            ->withoutMiddleware('verified');
    });

    /**
      * Recently Watched Movie
      */
    Route::prefix('recently-watched-movies')->group(function () 
    {
        Route::get('/', [RecentlyWatchedMoviesController::class, 'index']);
        Route::get('/{recentlyWatchedMovie}', [RecentlyWatchedMoviesController::class, 'show']);
        Route::post('/user-profiles/{id}', [RecentlyWatchedMoviesController::class, 'store']);
        Route::put('/', [RecentlyWatchedMoviesController::class, 'update']);
        Route::put('/position-millis', [RecentlyWatchedMoviesController::class, 'updatePositionMillis']);
        Route::delete('/', [RecentlyWatchedMoviesController::class, 'destroy']);
        Route::delete('/clear', [RecentlyWatchedMoviesController::class, 'destroy']);
    });

    /**
      * Subscriptions
      */
      Route::prefix('subscriptions')->group(function () 
      {
          Route::get('/', [SubscriptionsController::class, 'index']);
          Route::get('/{subscription}', [SubscriptionsController::class, 'show']);
          Route::post('/', [SubscriptionsController::class, 'store'])
            ->withoutMiddleware(['verified']);
          Route::put('/cancel', [SubscriptionsController::class, 'cancel']);
          Route::delete('/', [SubscriptionsController::class, 'destroy']);
      });

    /**
     * User Profile
     */
    Route::prefix('users')->group(function () 
    {
        Route::get('/', [UsersController::class, 'index']);
        Route::get('/via-token', [UsersController::class, 'getUserByToken'])->withoutMiddleware('permission:Manage Users');
        Route::put('/email', [UsersController::class, 'updateEmail'])->withoutMiddleware('permission:Manage Users');
        Route::put('/password', [UsersController::class, 'updatePassword'])->withoutMiddleware('permission:Manage Users');
        Route::post('/email-verification-code', [UsersController::class, 'sendEmailVerificationCode'])->withoutMiddleware('permission:Manage Users');
    });

    /**
      * User Profile
      */
    Route::prefix('user-profiles')->group(function () 
    {
        Route::get('/', [UserProfilesController::class, 'index']);
        Route::get('/{id}', [UserProfilesController::class, 'show']);
        Route::post('/', [UserProfilesController::class, 'store']);
        Route::post('/avatar-upload', [UserProfilesController::class, 'uploadAvatar']);
        Route::put('/{profile}', [UserProfilesController::class, 'update']);
        Route::put('/{profile}/pin-code', [UserProfilesController::class, 'managePinCode']);
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


