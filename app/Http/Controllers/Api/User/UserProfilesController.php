<?php

namespace App\Http\Controllers\Api\User;

use App\Models\UserProfile;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserProfile\StoreRequest;
use App\Http\Requests\UserProfile\UpdateRequest;
use App\Http\Requests\Upload\UploadAvatarRequest;

class UserProfilesController extends Controller
{
    use ApiResponser, HasUploadable;

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
                'myLists',
                'remindedComingSoonMovies',
                'myDownloads',
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

        $profileDetails->recently_watched_movies = $profileDetails->recentlyWatchedMovies->map->movie;

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
        $profile = UserProfile::create([
            'user_id' => $request->user('api')->id,
            'name' => $request->name,
            'is_for_kids' => $request->is_for_kids,
            'avatar' => $request->avatar
        ]);

        return $this->success($profile, 'Profile created successfully.');
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
        $profile->update($request->validated());

        return $this->success(null, 'Profile updated successfully.');
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
            UserProfile::$FILE_PATH,
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
        $profile->delete();

        return $this->success(null, 'Profile deleted successfully');
    }

}