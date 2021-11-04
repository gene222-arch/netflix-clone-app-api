<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\PaymentAuthorizationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class QueuePaymentAuthorizationNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $checkOutUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $checkOutUrl)
    {
        $this->user = $user;
        $this->checkOutUrl = $checkOutUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(
            new PaymentAuthorizationNotification($this->checkOutUrl)
        );

        event(new \App\Events\PaymentAuthorizationSentEvent($this->user, [
            'read_at' => NULL,
            'data' => [
                'type' => 'Payment Authorization Notification',
            ]
        ]));
    }
}
