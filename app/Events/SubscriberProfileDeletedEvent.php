<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SubscriberProfileDeletedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public int $profileId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, int $profileId)
    {
        $this->user = $user;
        $this->profileId = $profileId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('subscriber.profile.deleted.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'User Profile Deleted Event',
            'data' => [
                'profileId' => $this->profileId
            ]
        ];
    }
}
