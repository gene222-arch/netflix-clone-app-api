<?php

namespace App\Events;

use App\Models\ComingSoonMovie;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComingSoonMovieCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ComingSoonMovie $comingSoonMovie;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ComingSoonMovie $comingSoonMovie)
    {
        $this->comingSoonMovie = $comingSoonMovie;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('coming.soon.movie.created');
    }

    /**
     * Data to send
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'type' => 'Coming Soon Movie Created',
            'data' => $this->comingSoonMovie
        ];
    }
}
