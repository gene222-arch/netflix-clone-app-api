<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Subscription;
use App\Models\User;

trait SubscriptionServices
{
    public function preSubscription(string $type, int $userId)
    {
        $data = [
            'user_id' => $userId,
            'type' => $type
        ];

        switch ($type) 
        {
            case 'Basic':
                return Subscription::query()->create(array_merge(
                    $data,
                    [ 'cost' => 100 ]
                ));

            case 'Standard':
                return Subscription::query()->create(array_merge(
                    $data,
                    [ 'cost' => 200 ]
                ));

            case 'Premium':
                return Subscription::query()->create(array_merge(
                    $data,
                    [ 'cost' => 600 ]
                ));
        }
    }

    public function subscribe(int $userId, ?string $type = NULL)
    {
        $user = auth('api')->user();

        if (! $user) {
            $user = User::find($userId);
        }

        $totalSubscriptions = $user->subscriptions->where('subscribed_at', '!=', NULL)->count();

        if (! $totalSubscriptions) 
        {
            $currentPreSubscription = $user->subscriptions->first();
            $expiredAt = null;

            switch ($currentPreSubscription->type) 
            {
                case 'Basic':
                    $expiredAt = Carbon::now()->addMonths(2);
                    break;

                case 'Standard':
                    $expiredAt = Carbon::now()->addMonths(5);
                    break;

                case 'Premium':
                    $expiredAt = Carbon::now()->addMonths(7);
                    break;
            }

            $user->activeSubscription()->update([
                'is_first_subscription' => true,
                'expired_at' => $expiredAt,
                'subscribed_at' => Carbon::now()
            ]);
        }

        if ($totalSubscriptions && $type)
        {
            $expiredAt = null;

            switch ($type) 
            {
                case 'Basic':
                    $expiredAt = Carbon::now()->addMonth();
                    break;

                case 'Standard':
                    $expiredAt = Carbon::now()->addMonths(4);
                    break;

                case 'Premium':
                    $expiredAt = Carbon::now()->addMonths(6);
                    break;
            }

            $user->subscription()->create([
                'expired_at' => $expiredAt,
                'subscribed_at' => Carbon::now()
            ]);
        }
    }
}