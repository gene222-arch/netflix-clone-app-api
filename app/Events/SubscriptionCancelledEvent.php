<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCancelledEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public array $subscriptionDetails;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, array $subscriptionDetails)
    {
        $this->user = $user;
        $this->subscriptionDetails = $subscriptionDetails;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('subscription.cancelled.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'Subscription Cancelled Event',
            'data' => $this->subscriptionDetails
        ];
    }
}
