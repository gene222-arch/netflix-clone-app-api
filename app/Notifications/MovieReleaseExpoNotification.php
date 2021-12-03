<?php

namespace App\Notifications;

use App\Models\Movie;
use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class MovieReleaseExpoNotification extends Notification
{
    public Movie $movie;
    public bool $shouldRemindUser;

    public function __construct(Movie $movie, bool $shouldRemindUser)
    {
        $this->movie = $movie;
        $this->shouldRemindUser = $shouldRemindUser;
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {        
        $movieTitle = $this->movie->title;
        
        $titleSubContent = "$movieTitle is available in " . env('APP_NAME') . ".";
        $title = $this->shouldRemindUser ? "🔔 Reminder: $titleSubContent" : "📣 Release: $titleSubContent";

        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title($title)
            ->body("Start watching it now")
            ->setChannelId('movie-release-channel')
            ->priority('high');
    }
}
