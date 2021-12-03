<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class MovieReleaseExpoNotification extends Notification
{
    public string $movieTitle;
    public bool $shouldRemindUser;

    public function __construct(string $movieTitle, bool $shouldRemindUser)
    {
        $this->movieTitle = $movieTitle;
        $this->shouldRemindUser = $shouldRemindUser;
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {        
        $titleSubContent = "{$this->movieTitle} is available in " . env('APP_NAME') . ".";
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
