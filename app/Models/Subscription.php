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

    public function scopeIsExpired($query)
    {
        $expiredAt = $query->where([
            [ 'is_expired', '=', false ],
            [ 'subscribed_at', '!=', NULL ],
            [ 'expired_at', '!=', NULL ]
        ])
            ->first()
            ?->expired_at;

        if (! $expiredAt) return true;

        $today = Carbon::now();

        if (! ($today === $expiredAt || $expiredAt < $today)) return false;

        return boolval($query->update(['is_expired' => true]));
    }
}
