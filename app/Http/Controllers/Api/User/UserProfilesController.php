<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfile\StoreRequest;
use App\Http\Requests\UserProfile\UpdateRequest;
use App\Models\UserProfile;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\Auth;

class UserProfilesController extends Controller
{
    use ApiResponser;

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
     * @param  UserProfile $userProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserProfile $profile) 
    {
        $recentlyWatchedMovies = $profile
            ->recentlyWatchedMovies()
            ->with(['movie.userRatings' => fn($q) => $q->where('user_ratings.user_profile_id', $profile->id)])
            ->get()
            ->map
            ->movie;

        return $this->success([
            'profile' => $profile,
            'recently_watched_movies' => $recentlyWatchedMovies
        ]);
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