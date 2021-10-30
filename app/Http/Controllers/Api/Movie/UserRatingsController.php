<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Movie;
use App\Models\UserRating;
use App\Models\UserProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\UserRating\Request;
use App\Http\Requests\Movie\UserRating\DestroyRequest;
use App\Traits\Movie\HasUserRatingServices;

class UserRatingsController extends Controller
{
    use HasUserRatingServices;

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = UserRating::all();

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\UserRating\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = $this->storeRate($request);
        
        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Movie rate successfully.');
    }


    /**
     * Display the specified resource via user id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserRating $userRating)
    {
        return $this->success($userRating);
    }


    /**
     * Display the specified resource via user id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByUserID()
    {
        return $this->success(request()->user('api')->userRatings()->get());
    }


    /**
     * Display the specified resource via user profile id.
     *
     * @param  UserProfile  $userProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByUserProfileID(UserProfile $userProfile)
    {
        return $this->success($userProfile->userRatings()->get());
    }


    /**
     * Display the specified resource via user profile id.
     *
     * @param  Movie  $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByMovieID(Movie $movie)
    {
        return $this->success($movie->userRatings()->get());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        $this->destroyRate($request);

        return $this->success(null, 'Movie unrated successfully.');
    }
}
