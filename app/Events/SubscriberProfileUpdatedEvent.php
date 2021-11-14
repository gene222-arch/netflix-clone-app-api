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
use Jenssegers\Agent\Facades\Agent;

class SubscriberProfileUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public UserProfile $profile;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, UserProfile $profile)
    {
        $this->user = $user;
        $this->profile = $profile;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('subscriber.profile.updated.' . $this->user->id);
    }

    public function broadcastWith()
    {
        $agent = new \Jenssegers\Agent\Agent;

        $platform = $agent->browser() ? 'web' : 'android';

        return [
            'type' => 'Subscriber Profile Updated Event',
            'data' => $this->profile,
            'platform' => $platform
        ];
    }
}
