<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class NotifyUnSubscribedUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:user-unsubscribed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify user to update or renew their subscription if unsubscribed.';

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
        $subscribers = User::role('Subscriber')->get();

        $subscribers->each(function ($subscriber) 
        {
            $subscription = $subscriber->currentSubscription();

            if ($subscription && ($subscription->is_expired || $subscription->is_cancelled)) 
            {
                $subscriber->notify(
                    new \App\Notifications\UnSubscribedUserNotification()
                );
            }
        });
    }
}
