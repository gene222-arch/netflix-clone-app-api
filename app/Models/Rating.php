<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'model_type',
        'likes',
        'dislikes',
        'total_votes'
    ];
    
    /**
     * Define an inverse one-to-one or many relationship with Movie Class
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public static function incrementLike(int $movieId, string $modelType): Rating|bool
    {
        $rating = Rating::where([
            [ 'movie_id', $movieId ],
            [ 'model_type', $modelType ]
        ])->first();

        if (! $rating) {
            return Rating::create([
                'movie_id' => $movieId,
                'model_type' => $modelType,
                'likes' => 1,
                'total_votes' => 1
            ]);
        }

        return $rating->update([
            'likes' => DB::raw('likes + 1'),
            'total_votes' => DB::raw('total_votes + 1')
        ]);
    }

    public static function incrementDislike(int $movieId, string $modelType)
    {
        $rating = Rating::where([
            [ 'movie_id', $movieId ],
            [ 'model_type', $modelType ],
        ])->first();

        if (!$rating) {
            return Rating::create([
                'movie_id' => $movieId,
                'model_type' => $modelType,
                'dislikes' => 1,
                'total_votes' => 1
            ]);
        }
        
        return $rating->update([
            'dislikes' => DB::raw('dislikes + 1'),
            'total_votes' => DB::raw('total_votes + 1')
        ]);
    }

    public static function decrementLike(int $movieId, string $modelType)
    {
        return Rating::where([
            [ 'movie_id', $movieId ],
            [ 'model_type', $modelType ]
        ])
            ->update([
                'likes' => DB::raw('likes - 1'),
                'total_votes' => DB::raw('total_votes -1')
            ]);
    }

    public static function decrementDislike(int $movieId, string $modelType)
    {
        return Rating::where([
            [ 'movie_id', $movieId ],
            [ 'model_type', $modelType ]
        ])
            ->update([
                'dislikes' => DB::raw('dislikes - 1'),
                'total_votes' => DB::raw('total_votes - 1')
            ]);
    }

    public static function unrate(int $movieId, string $previousRate, string $modelType)
    {
        $rating = Rating::where([
            [ 'movie_id', $movieId ],
            [ 'model_type', $modelType ]
        ])->first();

        if ($previousRate === 'like') {
            return $rating->update([
                'likes' => DB::raw('likes - 1'),
                'total_votes' => DB::raw('total_votes - 1')
            ]);
        }

        if ($previousRate === 'dislike') {
            return $rating->update([
                'dislikes' => DB::raw('dislikes - 1'),
                'total_votes' => DB::raw('total_votes - 1')
            ]);
        }
    }
}
