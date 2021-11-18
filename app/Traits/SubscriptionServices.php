<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Subscription\UpdateRequest;

trait SubscriptionServices
{
    public function preSubscription(string $type, int $userId, string $paymentMethod): bool|string
    {
        try {
            DB::transaction(function () use ($type, $userId, $paymentMethod)
            {
                $subscription = NULL;
                $amount = 0;
        
                $data = [
                    'user_id' => $userId,
                    'type' => $type
                ];
        
                switch ($type) 
                {
                    case 'Basic':
                        $amount = 100;
                        $subscription = Subscription::query()->create(array_merge(
                            $data,
                            [ 'cost' => 100 ]
                        ));
        
                    case 'Standard':
                        $amount = 200;
                        $subscription = Subscription::query()->create(array_merge(
                            $data,
                            [ 'cost' => 200 ]
                        ));
        
                    case 'Premium':
                        $amount = 600;
                        $subscription = Subscription::query()->create(array_merge(
                            $data,
                            [ 'cost' => 600 ]
                        ));
                }
        
                $subscription->details()->create([
                    'payment_method' => $paymentMethod,
                    'paid_amount' => $amount
                ]);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function subscribe(string $userEmail, string $type, string $paymentMethod): bool|string
    {
        try {
            DB::transaction(function () use ($userEmail, $type, $paymentMethod) 
            {
                $user = auth('api')->user();
                $subscriptionDetails = [];
        
                if (! $user) {
                    $user = User::query()->firstWhere('email', '=', $userEmail);
                }
        
                $totalSubscriptions = $user->subscriptions->where('subscribed_at', '!=', NULL)->count();
                
                /** Has no subscriptions */
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
        
                    $subscriptionDetails = [
                        'is_first_subscription' => true,
                        'expired_at' => $expiredAt,
                        'is_expired' => false,
                        'subscribed_at' => Carbon::now(),
                        'status' => 'subscribed'
                    ];
        
                    $user->inActiveSubscription()->update($subscriptionDetails);
                }
        
                /** Has subscriptions */
                if ($totalSubscriptions && $type)
                {
                    $expiredAt = null;
                    $cost = 0;
        
                    switch ($type) 
                    {
                        case 'Basic':
                            $expiredAt = Carbon::now()->addMonth();
                            $cost = 100;
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
        
                    $subscriptionDetails = [
                        'type' => $type,
                        'cost' => $cost,
                        'is_expired' => false,
                        'expired_at' => $expiredAt,
                        'subscribed_at' => Carbon::now(),
                        'status' => 'subscribed'
                    ];
        
                    $subscription = $user->subscriptions()->create($subscriptionDetails);
                    $subscription->details()->create([
                        'payment_method' => $paymentMethod,
                        'paid_amount' => $cost
                    ]);
                }
        
                $user->notifications()
                    ->latest()
                    ->first()
                    ->markAsRead();
        
                event(new \App\Events\SubscribedSuccessfullyEvent($user, $subscriptionDetails + [
                    'days_left' => Carbon::createFromDate($expiredAt)->diffInDays()
                ]));
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function updateSubscription(string $type, string $userEmail, string $paymentMethod): bool|string
    {
        try {
            DB::transaction(function () use ($type, $userEmail, $paymentMethod) 
            {
                $user = User::query()->firstWhere('email', $userEmail);
                $userSubscription = $user->activeSubscription();
        
                $type = $type;
                $expiredAt = NULL;
                $cost = 0;
        
                switch ($type) 
                {
                    case 'Basic':
                        $expiredAt = Carbon::now()->addMonth();
                        $cost = 100;
                        break;
        
                    case 'Standard':
                        $expiredAt = Carbon::now()->addMonths(5);
                        $cost = 200;
                        break;
                    
                    case 'Premium':
                        $expiredAt = Carbon::now()->addMonths(6);
                        $cost = 600;
                        break;
                }
        
                $subscriptionDetails = [
                    'type' => $type,
                    'is_first_subscription' => false,
                    'subscribed_at' => Carbon::now(),
                    'expired_at' => $expiredAt,
                    'cost' => $cost,
                    'status' => 'subscribed'
                ];
        
                $result = $userSubscription->update($subscriptionDetails);
                $userSubscription->details()->create([
                    'payment_method' => $paymentMethod,
                    'paid_amount' => $cost
                ]);
        
                if ($result) {
                    event(new \App\Events\SubscribedSuccessfullyEvent($user, $subscriptionDetails + [
                        'days_left' => Carbon::createFromDate($expiredAt)->diffInDays()
                    ]));
                }
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }
}