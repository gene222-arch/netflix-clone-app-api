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

class UserProfilePINCodeUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public array $userProfileDetails;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, array $userProfileDetails)
    {
        $this->user = $user;
        $this->userProfileDetails = $userProfileDetails;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.profile.manage.pincode.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'UserProfilePINCodeUpdatedEvent',
            'data' => $this->userProfileDetails
        ];
    }
}
