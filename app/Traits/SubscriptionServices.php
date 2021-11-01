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

    public function subscribe(string $userEmail, string $type)
    {
        $user = auth('api')->user();

        $subscription = new Subscription();

        if (! $user) {
            $user = User::query()->firstWhere('email', '=', $userEmail);
        }

        $totalSubscriptions = $user->subscriptions->where('subscribed_at', '!=', NULL)->count();
        
        if (! $totalSubscriptions) 
        {
            $inActiveSubscription = $user->inActiveSubscription->first();
            $expiredAt = null;

            switch ($inActiveSubscription->type) 
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

            $subscription = [
                'is_first_subscription' => true,
                'expired_at' => $expiredAt,
                'subscribed_at' => Carbon::now()
            ];

            $user->inActiveSubscription()->update($subscription);
        }

        if ($totalSubscriptions && $type)
        {
            $expiredAt = null;
            $cost = 200;

            switch ($type) 
            {
                case 'Basic':
                    $expiredAt = Carbon::now()->addMonth();
                    break;

                case 'Standard':
                    $expiredAt = Carbon::now()->addMonths(4);
                    $cost = 400;
                    break;

                case 'Premium':
                    $expiredAt = Carbon::now()->addMonths(6);
                    $cost = 600;
                    break;
            }

            $subscription = [
                'type' => $type,
                'cost' => $cost,
                'expired_at' => $expiredAt,
                'subscribed_at' => Carbon::now()
            ];

            $user->subscriptions()->create($subscription);
        }

        event(new \App\Events\SubscribedSuccessfullyEvent($subscription));
    }
}