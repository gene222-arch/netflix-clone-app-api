<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleasedMovie extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'released_by_id',
        'movie_id',
        'coming_soon_movie_id',
        'released_at'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($releasedMovie) {
            $releasedMovie->released_by_id = auth('api')->user()->id;
            $releasedMovie->released_at = Carbon::now();
        });
    }

    public function releasedMovieNotifiedUser()
    {
        return $this->hasMany(ReleasedMovieNotifiedUser::class);
    }
}
