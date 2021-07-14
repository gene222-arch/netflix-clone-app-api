<?php

namespace App\Models;

use App\Models\Genre;
use App\Models\Author;
use App\Models\Director;
use App\Traits\Upload\HasUploadable;
use Illuminate\Database\Eloquent\Model;
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
        'title_logo_path',
        'video_size_in_mb'
    ];

        
    /**
    * Define a many-to-many relationship with Author class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany('movie_authors', Author::class);
    }

    /**
     * Define a many-to-many relationship with Cast class
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function casts(): BelongsToMany
    {
        return $this->belongsToMany('movie_casts', Cast::class);
    }

    /**
    * Define a many-to-many relationship with Director class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function directors(): BelongsToMany
    {
        return $this->belongsToMany('movie_directors', Director::class);
    }

    /**
    * Define a many-to-many relationship with Genre class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany('movie_genres', Genre::class);
    }
}
