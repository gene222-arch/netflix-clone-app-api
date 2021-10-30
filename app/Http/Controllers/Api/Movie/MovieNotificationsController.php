<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\MovieNotification;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class MovieNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = MovieNotification::with(
                'movie:id,title,wallpaper_path', 
                'releasedDetails:id,movie_id,coming_soon_movie_id'
            )
            ->where('created_at', '<=', Carbon::now()->addMonth())
            ->orderByDesc('created_at')
            ->get();

        return !$notifications->count() ? $this->noContent(): $this->success($notifications);
    }
}
