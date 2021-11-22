<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_profile_id',
        'movie_id',
        'model_type',
        'like',
        'dislike',
        'rate'
    ];

    public $timestamps = false;

    protected static function disliked(int $movieID, int $userProfileID, int $userId, string $modelType): UserRating
    {
        return self::create([
            'movie_id' => $movieID,
            'user_id' => Auth::user()->id,
            'user_profile_id' => $userProfileID,
            'like' => false,
            'dislike' => true,
            'rate' => 'dislike'
        ]);
    }

    protected static function unrate(int $movieID, int $userProfileID, string $modelType): string
    {
        $userRatedMovie = self::where([
                [ 'movie_id', $movieID ],
                [ 'model_type', $modelType ],
                [ 'user_profile_id', $userProfileID ]
            ])
            ->first();

        $userRatedMovie->delete();

        return $userRatedMovie->rate;
    }
}
