<?php

namespace App\Traits\Movie;

use App\Http\Requests\Movie\UserRating\DestroyRequest;
use App\Models\Rating;
use App\Models\UserRating;
use App\Models\MovieReport;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Movie\UserRating\Request;

trait HasUserRatingServices
{
    
    /**
     * Create or update ratings, user_ratings, and movie_reports tables 
     *
     * @param  \App\Http\Requests\Movie\UserRating\Request $request
     * @return mixed
     */
    public function storeRate(Request $request): mixed
    {
        try {
            DB::transaction(function () use($request)
            {
                $movieId = $request->movie_id;
                $userProfileId = $request->user_profile_id;
                $rate = $request->rate;

                switch ($rate) 
                {
                    case 'like':
                        Rating::incrementLike($movieId);

                        $isMovieRated = MovieReport::where('movie_id', $movieId)->first();

                        if (! $isMovieRated) {
                            MovieReport::create([
                                'movie_id' => $movieId,
                                'total_likes_within_a_day' => 1,
                                'total_likes_within_a_week' => 1
                            ]);
                        } else {
                            $isMovieRated
                                ->update([
                                    'total_likes_within_a_day' => DB::raw('total_likes_within_a_day + 1'),
                                    'total_likes_within_a_week' => DB::raw('total_likes_within_a_week + 1')
                                ]);
                        }

                        UserRating::liked($movieId, $userProfileId);
                        break;

                    case 'dislike':
                        Rating::incrementDislike($movieId);
                        UserRating::disliked($movieId, $userProfileId);
                        break;

                    default: 
                        MovieReport::where('movie_id', $movieId)->update([
                            'total_likes_within_a_day' => DB::raw('total_likes_within_a_day - 1'),
                            'total_likes_within_a_week' => DB::raw('total_likes_within_a_week - 1')
                        ]);
                        $previouseRate = UserRating::unrate($movieId, $userProfileId);
                        Rating::unrate($movieId, $previouseRate);
                }
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return true;
    }

    /**
     *
     * @param  \App\Http\Requests\Movie\UserRating\DestroyRequest $request
     * @return void
     */
    public function destroyRate(DestroyRequest $request): void
    {
        $userId = $request->user()->id;
        $userProfileId = $request->user_profile_id;
        $movieId = $request->movie_id;

        $userRating = UserRating::where([
            ['user_id', $userId],
            ['user_profile_id', $userProfileId],
            ['movie_id', $movieId]
        ])->first();

        /** Check for previous rate to a movie then decrement it accordingly */
        if ($userRating->rate === 'like') {
            Rating::decrementLike($movieId);
        }
        if ($userRating->rate === 'dislike') {
            Rating::decrementDislike($movieId);
        }

        $userRating->delete();
    }
}