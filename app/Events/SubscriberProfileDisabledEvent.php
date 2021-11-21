<?php

namespace App\Events;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class SubscriberProfileDisabledEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public array $profileIds;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, array $profileIds)
    {
        $this->user = $user;
        $this->profileIds = $profileIds;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('subscriber.profile.disabled.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'Subscriber Profile Disabled Event',
            'data' => [
                'profileIds' => $this->profileIds
            ]
        ];
    }
}
