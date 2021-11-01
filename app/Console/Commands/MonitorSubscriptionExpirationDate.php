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
        $currentDate = Carbon::now();
        $users = User::role('Subscriber')->get();
        $today = Carbon::parse($currentDate)->format('m/d/Y H:i:s');

        $users->map(function ($user) use ($today, $currentDate) 
        {
            $subscription = $user->activeSubscription();

            if ($subscription)
            {
                $expirationDate = Carbon::parse($subscription->expired_at)->format('m/d/Y H:i:s');
            
                if ($today >= $expirationDate) 
                {
                    $subscription->update([
                        'is_expired' => true,
                        'expired_at' => $currentDate,
                        'status' => 'expired'
                    ]);
                }
            }
        });
    }
}
