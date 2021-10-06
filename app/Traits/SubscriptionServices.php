<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Subscription;

trait SubscriptionServices
{
    public function subscribe(string $type, ?int $userId = null)
    {
        $data = [
            'type' => $type
        ];

        if ($userId) {
            $data = array_merge($data, [
                'user_id' => $userId
            ]);
        }

        switch ($type) 
        {
            case 'Basic':
                return Subscription::query()->create(array_merge(
                    $data,
                    [
                        'expired_at' => Carbon::now()->addMonth(),
                        'cost' => 100
                    ]
                ));

            case 'Standard':
                return Subscription::query()->create(array_merge(
                    $data,
                    [
                        'expired_at' => Carbon::now()->addMonths(2),
                        'cost' => 200
                    ]
                ));

            case 'Premium':
                return Subscription::query()->create(array_merge(
                    $data,
                    [
                        'expired_at' => Carbon::now()->addMonths(6),
                        'cost' => 600
                    ]
                ));
        }
    }
}