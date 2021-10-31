<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\MyList;
use App\Models\RemindMe;
use App\Models\MyDownload;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Jobs\QueuePasswordResetNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Jobs\QueueEmailVerificationNotification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar_path',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function markedAsActive()
    {
        $this->update([
            'is_active' => true
        ]);

        $dateExist = $this
            ->subscriberActiveLogs
            ->filter(function($subs) 
            {
                $activeAt = Carbon::parse($subs->active_at)->format('M/d/Y');
                $currentDate = Carbon::parse(Carbon::now())->format('M/d/Y');

                return ( $activeAt === $currentDate ) && ( $subs->user_id === $this->id );
            });

        if ($this->hasRole('Subscriber') && !$dateExist->count()) {
            $this->subscriberActiveLogs()->create();
        }
    }

    public function markedAsInActive()
    {
        $this->update([
            'is_active' => false
        ]);
    }

    /**
     * Dispatch a password reset notification
     * 
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        dispatch(new QueuePasswordResetNotification($this, $token))
            ->delay(now()->addSeconds(10));
    }

    public function subscriberActiveLogs()
    {
        return $this->hasMany(SubscriberActiveLogs::class, 'user_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->where('is_expired', false);
    }

    public function getKey(): int
    {
        return $this->id;
    }
    
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Send an email notification verification
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void 
    {
        $this->notify(new EmailVerificationNotification());
    }

    /**
     * Send an email notification verification
     *
     * @return void
     */
    public function sendPaymentAuthorizationNotification(string $checkOutUrl): void 
    {
        dispatch(
            new \App\Jobs\QueuePaymentAuthorizationNotification($this, $checkOutUrl)
        )
        ->delay(2);
    }

    /**
     * Send an email notification verification in queue
     *
     * @return void
     */
    public function sendQueueEmailVerificationNotification(): void 
    {
        dispatch(
            new QueueEmailVerificationNotification($this)
        )
        ->delay(3);
    }
    
    /** RELATIONSHIPS */

    /**
     * Define a one-to-one relationship with UserAddress class
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class);
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

    public function findProfileMyList(int $id)
    {
        return $this->myLists()->where('user_profile_id', $id);
    }

    /**
     * Define a one-to-many relationship with UserProfile Class
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

        
    /**
     * Find Profile By Id
     *
     * @param  integer $id
     * @return UserProfile
     */
    public function findProfileById(int $id): UserProfile
    {
        return $this->profiles()->find($id);
    }

    /**
     * Define a one-to-many relationship with MyDownload Class
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myDownloads(): HasMany
    {
        return $this->hasMany(MyDownload::class);
    }

    public function findDownloadsByProfileId(int $id): HasMany
    {
        return $this->myDownloads()->where('user_profile_id', $id);
    }

    /**
    * Define a many-to-many relationship with RemindMe class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function recentlyWatchedMovies(): HasMany
    {
        return $this->hasMany(RecentlyWatchedMovie::class);
    }

    public function findRecentWatchesByProfileId(int $id): HasMany
    {
        return $this->recentlyWatchedMovies()->where('user_profile_id', $id);
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
    * Define a many-to-many relationship with Model class
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function userRatings(): HasMany
    {
        return $this->hasMany(UserRating::class);
    }


}
