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
                $modelType = $request->model_type;
                $userProfileId = $request->user_profile_id;
                $rate = $request->rate;
                $userId = $request->user('api')->id;

                switch ($rate) 
                {
                    case 'like':
                        if ($modelType === 'Movie') 
                        {
                            $movieReport = MovieReport::where('movie_id', $movieId);

                            if (! $movieReport) {
                                MovieReport::create([
                                    'movie_id' => $movieId,
                                    'total_likes_within_a_day' => 1,
                                    'total_likes_within_a_week' => 1
                                ]); 
                            } else {
                                $movieReport
                                    ->update([
                                        'total_likes_within_a_day' => DB::raw('total_likes_within_a_day + 1'),
                                        'total_likes_within_a_week' => DB::raw('total_likes_within_a_week + 1')
                                    ]);
                            }
                        }

                        Rating::incrementLike($movieId, $modelType);

                        UserRating::create([
                            'movie_id' => $movieId,
                            'model_type' => $modelType,
                            'user_id' => $userId,
                            'user_profile_id' => $userProfileId,
                            'like' => true,
                            'dislike' => false,
                            'rate' => 'like'
                        ]);
                        
                        break;

                    case 'dislike':
                        Rating::incrementDislike($movieId, $modelType);
                        
                        UserRating::create([
                            'movie_id' => $movieId,
                            'model_type' => $modelType,
                            'user_id' => $userId,
                            'user_profile_id' => $userProfileId,
                            'like' => false,
                            'dislike' => true,
                            'rate' => 'dislike'
                        ]);
                        break;

                    default: 
                        
                        if ($modelType === 'Movie') 
                        {
                            MovieReport::where('movie_id', $movieId)
                                ->update([
                                    'total_likes_within_a_day' => DB::raw('total_likes_within_a_day - 1'),
                                    'total_likes_within_a_week' => DB::raw('total_likes_within_a_week - 1')
                                ]);
                        }

                        $previouseRate = UserRating::unrate($movieId, $userProfileId, $modelType);
                        Rating::unrate($movieId, $previouseRate, $modelType);
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
        $modelType = $request->model_type;

        $userRating = UserRating::where([
            ['user_id', $userId],
            ['user_profile_id', $userProfileId],
            ['movie_id', $movieId]
        ])->first();

        /** Check for previous rate to a movie then decrement it accordingly */
        if ($userRating->rate === 'like') {
            Rating::decrementLike($movieId, $modelType);
        }
        if ($userRating->rate === 'dislike') {
            Rating::decrementDislike($movieId, $modelType);
        }

        $userRating->delete();
    }
}