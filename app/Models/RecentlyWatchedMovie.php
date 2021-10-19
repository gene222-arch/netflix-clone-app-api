<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecentlyWatchedMovie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_profile_id',
        'movie_id',
        'recently_watched_at',
        'last_played_position_millis'
    ];

    public $timestamps = false;

    /**
     * Define an inverse one-to-one or many relationship with Movie class
     *
     * @return BelongsTo
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
