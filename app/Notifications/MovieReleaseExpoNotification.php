<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class MovieReleaseExpoNotification extends Notification
{
    use Queueable;

    public string $movieTitle;

    public function __construct(string $movieTitle)
    {
        $this->movieTitle = $movieTitle;
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
            ->title("Release")
            ->body($this->movieTitle . " is Released")
            ->setChannelId('movie-release-channel');
    }
}
