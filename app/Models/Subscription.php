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
        'status'
    ];

    protected $appends = [
        'days_left'
    ];

    public function scopeIsExpired($query): bool
    {
        $subscription = $query->get()->last();
        $today = Carbon::parse(Carbon::now())->format('m/d/Y H:i:s');
        $expirationDate = Carbon::parse($subscription->expired_at)->format('m/d/Y H:i:s');

        if ($subscription->is_expired) return true;

        if ($today >= $expirationDate) 
        {
            $payload = [
                'is_expired' => true,
                'expired_at' => $today,
                'status' => 'expired'
            ];

            return $query->update($payload);
        }

        return false;
    }

    public function getDaysLeftAttribute()
    {
        return Carbon::createFromDate($this->expired_at)->diffInDays();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(SubscriptionDetail::class, 'subscription_id');
    }
}
