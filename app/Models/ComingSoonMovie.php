<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Cast;
use App\Models\Genre;
use App\Models\Director;
use Illuminate\Support\Str;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComingSoonMovie extends Model
{
    use HasFactory, HasUploadable;

    protected $fillable = [
        'title',
        'plot',
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
        'video_trailer_path',
        'title_logo_path',
        'status'
    ];


    protected $hidden = [
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        self::created(function ($comingSoonMovie) {
            static::cacheToForget();
            event(new \App\Events\ComingSoonMovieCreatedEvent($comingSoonMovie));
        });

        static::updating(function() {
            static::cacheToForget();
        });

        static::deleting(function() {
            static::cacheToForget();
        });
    }

    private static function cacheToForget() {
        Cache::forget('coming.soon.movies.index');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('yyyy');
    }

        
    /**
    * Define a many-to-many relationship with Author class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'coming_soon_movie_authors');
    }

    /**
     * Define a many-to-many relationship with Cast class
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function casts(): BelongsToMany
    {
        return $this->belongsToMany(Cast::class, 'coming_soon_movie_casts');
    }

    /**
    * Define a many-to-many relationship with Director class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(Director::class, 'coming_soon_movie_directors');
    }

    /**
    * Define a many-to-many relationship with Genre class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'coming_soon_movie_genres');
    }
    
    /**
    * Define a many-to-many relationship with Model class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function trailers(): HasMany
    {
        return $this->hasMany(Trailer::class);
    }
}
