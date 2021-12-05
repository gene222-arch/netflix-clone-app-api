<?php 

namespace App\ExpoNotificationServices;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class MovieReleasedExpoNotificationService
{
    public static function notify(string $movieTitle, int $comingSoonMovieId)
    {
        $userIds = DB::table('exponent_push_notification_interests')
            ->get()
            ->map(fn ($exponent) => (int) str_replace('App.Models.User.', '', $exponent->key));

        $users = User::query()->findMany($userIds);

        $users->map(function ($user) use($movieTitle, $comingSoonMovieId)
        {
            $shouldRemindUser = false;
            $comingSoonMovies = $user->remindMes;

            if ($comingSoonMovies->count()) 
            {
                $shouldRemindUser = $comingSoonMovies
                    ->map
                    ->coming_soon_movie_id
                    ->contains($comingSoonMovieId);
            }

            $user
                ->notify(
                    new \App\Notifications\MovieReleaseExpoNotification(
                        $movieTitle, 
                        $shouldRemindUser
                    )
                );
        });
    }
}