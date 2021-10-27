<?php

namespace App\Notifications;

use App\Models\Movie;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class MovieReleaseExpoNotification extends Notification
{
    use Queueable;

    public Movie $movie;

    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {        
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title('Release 📣')
            ->body($this->movie->title . " is Released")
            ->setJsonData([
                'type' => 'MovieReleaseExpoNotification'
            ])
            ->setChannelId('movie-release-channel')
            ->priority('high');
    }
}
