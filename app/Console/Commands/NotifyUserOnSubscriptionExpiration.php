<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;

class NotifyUserOnSubscriptionExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:notify-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify user`s subscription expiration date';

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

        $users->map(function ($user)
        {
            $subscription = $user->activeSubscription();

            if ($subscription)
            {
                $expiredAt = Carbon::parse($subscription->expired_at);
                $daysBeforeExpiration = $expiredAt->diffInDays(Carbon::today());
                
                if ($daysBeforeExpiration <= 7 && $daysBeforeExpiration > 0) {
                    $user->notify(
                        new \App\Notifications\SubscriptionDueDateNotification($expiredAt, $daysBeforeExpiration)
                    );
                }
            }
        });
    }
}
