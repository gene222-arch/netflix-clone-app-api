<?php

namespace App\Models;

use App\Models\MyList;
use App\Models\RemindMe;
use App\Models\MyDownload;
use App\Models\RecentlyWatchedMovie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'avatar',
        'is_for_kids'  
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public static string $FILE_PATH = 'images/user-profiles';

    /** RELATIONSHIPS */

    /**
    * Define a one-to-many relationship with MyDownload class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function myDownloads(): HasMany
    {
        return $this->hasMany(MyDownload::class);
    }


    /**
    * Define a many-to-many relationship with MyList class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function myLists(): HasMany
    {
        return $this->hasMany(MyList::class);
    }

    
    /**
     * Define a one-to-many relationship with RecentlyWatchedMovie class
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recentlyWatchedMovies(): HasMany
    {
        return $this->hasMany(RecentlyWatchedMovie::class);
    }


    /**
    * Define a many-to-many relationship with RemindMe class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function remindedComingSoonMovies(): HasMany
    {
        return $this->hasMany(RemindMe::class);
    }


    /**
     * Define an inverse one-to-one or many relationship with User Class.
     *
     * @return lluminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
    * Define a many-to-many relationship with Model class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function userRatings(): HasMany
    {
        return $this->hasMany(UserRating::class);
    }

    public function likedMovies()
    {
        return $this
            ->userRatings()
            ->where([
                ['model_type', 'Movie'],
                [ 'rate', 'like' ]
            ]);
    }

    public function likedComingSoonMovies()
    {
        return $this
            ->userRatings()
            ->where([
                ['model_type', 'ComingSoonMovie'],
                [ 'rate', 'like' ]
            ]);
    }
}
