<?php

namespace App\Models;

use App\Models\Cast;
use App\Models\Genre;
use App\Models\Director;
use App\Traits\Upload\HasUploadable;
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

        
    /**
    * Define a many-to-many relationship with Author class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany('coming_soon_movie_authors', Author::class);
    }

    /**
     * Define a many-to-many relationship with Cast class
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function casts(): BelongsToMany
    {
        return $this->belongsToMany('coming_soon_movie_casts', Cast::class);
    }

    /**
    * Define a many-to-many relationship with Director class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function directors(): BelongsToMany
    {
        return $this->belongsToMany('coming_soon_movie_directors', Director::class);
    }

    /**
    * Define a many-to-many relationship with Genre class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany('coming_soon_movie_genres', Genre::class);
    }

    /**
    * Define a many-to-many relationship with Model class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function trailers(): HasMany
    {
        return $this->hasMany('coming_soon_movie_trailers');
    }
}
