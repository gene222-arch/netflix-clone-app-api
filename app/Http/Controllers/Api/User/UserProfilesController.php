<?php

namespace App\Http\Controllers\Api\User;

use App\Models\UserProfile;
use App\Http\Controllers\Controller;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\UserProfile\StoreRequest;
use App\Http\Requests\UserProfile\UpdateRequest;
use App\Http\Requests\Upload\UploadAvatarRequest;
use App\Http\Requests\UserProfile\DisableRequest;
use App\Http\Requests\UserProfile\ManagePinCodeRequest;

class UserProfilesController extends Controller
{
    use HasUploadable;

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Display a listing of the user's profiles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $profiles = request()->user()->profiles;

        return !$profiles
            ? $this->noContent()
            : $this->success($profiles, 'User`s profiles fetched successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id) 
    {
        $profileDetails = UserProfile::with([
                'myLists.movie',
                'remindedComingSoonMovies',
                'myDownloads.movie',
                'likedMovies',
                'likedComingSoonMovies',
                'recentlyWatchedMovies.movie.userRatings' => function($q) use($id) {
                    return $q->where([
                        [ 'user_ratings.user_profile_id', $id ],
                        [ 'user_ratings.model_type', 'Movie' ],
                    ]);
                },
            ])
            ->find($id);

        $profileDetails->recently_watched_movies = $profileDetails
            ->recentlyWatchedMovies
            ->map(function ($recentlyWatchedMovie) 
            {
                $movie = $recentlyWatchedMovie->movie;
                $movie->last_played_position_millis = $recentlyWatchedMovie->last_played_position_millis;
                $movie->duration_in_millis = $recentlyWatchedMovie->duration_in_millis;

                return $movie;
            });

        unset($profileDetails->recentlyWatchedMovies);

        return $this->success($profileDetails);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $profile = UserProfile::create($request->validated());

        return $this->success($profile, 'Profile created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserProfile\ManagePinCodeRequest  $request
     * @param  UserProfile  $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function managePinCode(ManagePinCodeRequest $request, UserProfile $profile)
    {
        $data = $request->validated();
        $isUpdated = $profile->update($data);

        if (! $isUpdated) return $this->error();

        event(new \App\Events\UserProfilePINCodeUpdatedEvent($request->user('api'), $data));

        return $this->success(null, 'Profile Lock updated successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  UserProfile  $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, UserProfile $profile)
    {
        $data = $request->validated() + [
            'previous_avatar' => $profile->avatar
        ];

        $profile->update($data);

        return $this->success(null, 'Profile updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserProfile\DisableRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(DisableRequest $request)
    {
        $user = $request->user('api');

        $user
            ->profiles()
            ->whereIn('id', $request->ids)
            ->update([
                'enabled' => 0
            ]);

        event(new \App\Events\SubscriberProfileDisabledEvent($user, $request->ids));

        return $this->success(NULL, 'Profiles disabled successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Upload\UploadAvatarRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $path = $this->upload(
            $request,
            'avatar',
            'users/' . $request->user()->id . '/user-profiles/',
            320,
            320 
        );

        return $this->success($path, 'Avatar uploaded successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserProfile $profile)
    {
        $profile->myDownloads()->delete();
        $profile->myLists()->delete();
        $profile->recentlyWatchedMovies()->delete();
        $profile->remindedComingSoonMovies()->delete();
        $profile->delete();

        return $this->success(null, 'Profile deleted successfully');
    }

}