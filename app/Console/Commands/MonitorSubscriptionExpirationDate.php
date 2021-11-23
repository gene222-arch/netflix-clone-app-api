<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;

class MonitorSubscriptionExpirationDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:monitor-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor user`s subscription expiration date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::role('Subscriber')->get();
        $today = Carbon::now();

        $users->map(function ($user) use ($today) 
        {
            $subscription = $user->activeSubscription();

            if ($subscription)
            {
                $expiredAt = Carbon::parse($subscription->expired_at);
                
                if ($today->gte($expiredAt)) 
                {
                    $subscription->update([
                        'is_expired' => true,
                        'status' => 'expired'
                    ]);

                    $user->notify(
                        new \App\Notifications\SubscriptionExpiredExpoNotification()
                    );

                    event(new \App\Events\SubscriptionExpiredEvent($user, [
                        'is_expired' => true,
                        'expired_at' => $today,
                        'status' => 'expired',
                    ]));
                }
            }
        });
    }
}
