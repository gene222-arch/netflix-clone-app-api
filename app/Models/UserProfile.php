<?php

namespace App\Models;

use App\Models\MyList;
use App\Models\RemindMe;
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

    /** RELATIONSHIPS */

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
    public function remindMes(): HasMany
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
}
