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
        $authUser = auth('api')->user();

        $upcomingMovieIsInReminded = $authUser
            ->remindMes
            ->where(
                'coming_soon_movie_id', 
                '=', 
                $this->comingSoonMovieId
            )
            ->isNotEmpty();

        $movieTitle = $this->movie->title;
        $titleSubContent = "a new movie called $movieTitle is available in " . env('APP_NAME') . ".";

        $title = $upcomingMovieIsInReminded 
            ? "🔔 Reminder: $titleSubContent" 
            : "📣 Release: $titleSubContent";

        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title($title)
            ->body("Start watching it now")
            ->setJsonData([
                'type' => 'Movie Release Expo Notification',
                'movie' => $this->movie,
                'coming_soon_movie_id' => $this->comingSoonMovieId
            ])
            ->setChannelId('movie-release-channel')
            ->priority('high');
    }
}
