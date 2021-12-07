<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\MovieNotification;
use App\Http\Controllers\Controller;

class MovieNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isForKids = request()->input('isForKids', false);

        $operation = $isForKids ? '<=' : '>=';
        $ageRestriction = $isForKids ? 12 : 0;

        $notifications = MovieNotification::query()
            ->with([
                'movie' => fn($q) => $q->where('age_restriction', $operation, $ageRestriction), 
                'releasedDetails:id,movie_id,coming_soon_movie_id'
            ])
            ->whereRaw("DATE_ADD(created_at, INTERVAL 30 DAY) >= now()")
            ->orderByDesc('created_at')
            ->get();

        $filteredNotifications = $notifications->filter->movie->values();

        return !$filteredNotifications->count() ? $this->noContent(): $this->success($filteredNotifications);
    }
}
