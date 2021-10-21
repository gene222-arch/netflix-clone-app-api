<?php

namespace App\Models;

use App\Models\Genre;
use App\Models\Author;
use App\Models\Director;
use Illuminate\Support\Str;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    use HasFactory, HasUploadable;

    protected $fillable = [
        'title',
        'plot',
        'year_of_release',
        'date_of_release',
        'duration_in_minutes',
        'age_restriction',
        'country',
        'language',
        'casts',
        'genres',
        'directors',
        'authors',
        'poster_path',
        'wallpaper_path',
        'video_path',
        'video_preview_path',
        'title_logo_path',
        'video_size_in_mb'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($movie) 
        {
            static::cacheToForget();
            event(new \App\Events\MovieCreatedEvent($movie));
            MovieNotification::query()->create([ 'movie_id' => $movie->id ]);
        });
    }

    public static function cacheToForget()
    {
        Cache::forget('movies.index');
        Cache::forget('movies.categorizedMovies');
        Cache::forget('movies.latestTwenty');
    }

        
    /**
    * Define a many-to-many relationship with Author class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'movie_authors');
    }

    /**
     * Define a many-to-many relationship with Cast class
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function casts(): BelongsToMany
    {
        return $this->belongsToMany(Cast::class, 'movie_casts');
    }

    /**
    * Define a many-to-many relationship with Director class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(Director::class, 'movie_directors');
    }

    /**
    * Define a many-to-many relationship with Genre class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }

    /**
     * Define a one-to-one relationship with Rating Class
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }

    /**
     * Define a one-to-one relationship with MovieReport Class
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function report(): HasOne
    {
        return $this->hasOne(MovieReport::class);
    }


    public function similarMovies()
    {
        return $this->hasMany(SimilarMovie::class, 'model_id')->where('model_type', 'App\\Models\\Movie');
    }


    /**
    * Define a many-to-many relationship with UserRating class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function userRatings(): HasMany
    {
        return $this->hasMany(UserRating::class);
    }

    public function trailer()
    {
        return $this->hasOne(ComingSoonMovie::class, 'title', 'title');
    }
}
