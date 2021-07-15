<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\User;
use App\Models\Movie;
use App\Models\Rating;
use App\Models\UserRating;
use App\Models\UserProfile;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Movie\UserRating\Request;
use App\Http\Requests\Movie\UserRating\DestroyRequest;

class UserRatingsController extends Controller
{
    use ApiResponser;

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
        try {
            DB::transaction(function () use($request)
            {
                $movieID = $request->movie_id;
                $userProfileID = $request->user_profile_id;
                $rate = $request->rate;

                switch ($rate) 
                {
                    case 'like':
                        Rating::incrementLike($movieID);
                        UserRating::liked($movieID, $userProfileID);
                        break;
                    
                    case 'dislike':
                        Rating::incrementDislike($movieID);
                        UserRating::disliked($movieID, $userProfileID);
                        break;
                }
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
        
        return $this->success(null, 'Movie rated successfully.');
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
        return $this->success(Auth::user()->userRatings()->get());
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
        $userRating = UserRating::where([
            ['user_id', Auth::user()->id],
            ['user_profile_id', $request->user_profile_id],
            ['movie_id', $request->movie_id]
        ])
        ->first();

        if ($userRating->rate === 'like') {
            Rating::decrementLike($request->movie_id);
        }
        if ($userRating->rate === 'dislike') {
            Rating::decrementDislike($request->movie_id);
        }

        $userRating->delete();

        return $this->success(null, 'Movie unrated successfully.');
    }
}
