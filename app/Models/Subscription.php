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
        'is_first_subscription',
        'subscribed_at',
        'expired_at',
        'cancelled_at'
    ];

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($subscription) 
        {
            $subscription->user_id = auth('api')->id();
            $subscription->subscribed_at = Carbon::now();
            $subscription->cancelled_at = null;

            if (! auth('api')->user()->subscriptions->count()) 
            {
                $subscription->is_first_subscription = true;

                $subscription->expired_at = $subscription->expired_at->addMonth();
            }
        });
    }
}
