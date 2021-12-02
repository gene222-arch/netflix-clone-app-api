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
    public int $comingSoonMovieId;

    public function __construct(Movie $movie, int $comingSoonMovieId)
    {
        $this->movie = $movie;
        $this->comingSoonMovieId = $comingSoonMovieId;
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {        
        $exists = $notifiable
            ->remindMes()
            ->where('coming_soon_movie_id', '=', $this->comingSoonMovieId)
            ->exists();

        $movieTitle = $this->movie->title;
        
        $titleSubContent = "$movieTitle is available in " . env('APP_NAME') . ".";
        $title = $exists ? "🔔 Reminder: $titleSubContent" : "📣 Release: $titleSubContent";

        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title($title)
            ->body("Start watching it now")
            ->setChannelId('movie-release-channel')
            ->priority('high');
    }
}
