<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Subscription;

trait SubscriptionServices
{
    public function subscribe(string $type)
    {
        switch ($type) 
        {
            case 'Basic':
                return Subscription::query()->create([ 
                    'type' => $type,
                    'expired_at' => Carbon::now()->addMonth()
                ]);

            case 'Standard':
                return Subscription::query()->create([ 
                    'type' => $type,
                    'expired_at' => Carbon::now()->addMonths(2) 
                ]);

            case 'Premium':
                return Subscription::query()->create([ 
                    'type' => $type,
                    'expired_at' => Carbon::now()->addMonths(6) 
                ]);
        }
    }
}