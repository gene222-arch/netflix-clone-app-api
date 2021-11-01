<?php

namespace App\Console\Commands;

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
        $groupedUsers = User::all(['id'])->chunk(50);
        $today = Carbon::parse(Carbon::now())->format('m/d/Y H:i:s');


        foreach ($groupedUsers as $groupedUser) 
        {
            foreach ($groupedUser as $user) 
            {
                $subscription = $user->currentSubscription();

                $expirationDate = Carbon::parse($subscription->expired_at)->format('m/d/Y H:i:s');

                if ($today >= $expirationDate) 
                {
                    $payload = [
                        'is_expired' => true,
                        'expired_at' => $today,
                        'status' => 'expired'
                    ];
        
                    $query->update($payload);
                }
            }
        }
    }
}
