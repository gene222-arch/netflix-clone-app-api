<?php

namespace App\Services;

use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class RatingService 
{
    public static function onReleaseRelocate(int $comingSoonMovieId, int $releasedMovieId): string|bool
    {
        try {
            DB::transaction(function () use ($comingSoonMovieId, $releasedMovieId) 
            {
                $rating = Rating::firstWhere([
                    [ 'model_type', '=', 'ComingSoonMovie' ],
                    [ 'movie_id', '=', $comingSoonMovieId ]
                ]);
        
                if ($rating) {
                    $rating->update([
                        'model_type' => 'Movie',
                        'movie_id' => $releasedMovieId
                    ]);
                }
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }
}