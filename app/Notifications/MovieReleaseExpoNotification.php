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
        $isInRemindedComingSoonMovies = $notifiable
            ->remindMes
            ->map
            ->coming_soon_movie_id
            ->search($this->comingSoonMovieId);

        $title = !$isInRemindedComingSoonMovies ? 'Release 📣' : 'Reminder 🔔';
        
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title($title)
            ->body($this->movie->title . " is Released")
            ->setChannelId('movie-release-channel')
            ->priority('high');
    }
}
