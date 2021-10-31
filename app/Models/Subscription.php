<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type',
        'cost',
        'is_first_subscription',
        'is_cancelled',
        'is_expired',
        'is_subscribed',
        'expired_at',
        'cancelled_at',
        'subscribed_at',
    ];

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($subscription) 
        {
            $authenticatedUser = auth('api')->user();

            if ($authenticatedUser) // Create subscription using authenticated user 
            {
                $subscription->user_id = auth('api')->id();
                
                if (! $authenticatedUser->subscriptions->count()) 
                {
                    $subscription->is_first_subscription = true;
                    $subscription->expired_at = $subscription->expired_at->addMonth();
                }
            } 
            else // Create subscription by passing user id
            {
                if (! self::find($subscription->user_id)) 
                {
                    $subscription->is_first_subscription = true;
                    $subscription->expired_at = $subscription->expired_at->addMonth();
                }
            }

            $subscription->subscribed_at = Carbon::now();
            $subscription->cancelled_at = null;
        });
    }

    public function scopeIsExpired($query)
    {
        $expiredAt = $query->where('is_expired', false)->first()?->expired_at;

        if (! $expiredAt) return true;

        $today = Carbon::now();

        if (! ($today === $expiredAt || $expiredAt < $today)) return false;

        return boolval($query->update(['is_expired' => true]));
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPaymentDetail::class)->orderBy('paid_at', 'asc');
    }
}
