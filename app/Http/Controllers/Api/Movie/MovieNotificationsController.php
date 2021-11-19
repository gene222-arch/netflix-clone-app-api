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
        $isForKids = (bool) request()->input('isForKids');

        $operation = $isForKids ? '<=' : '>=';
        $ageRestriction = $isForKids ? 12 : 0;

        $notifications = MovieNotification::with([
            'movie' => fn($q) => $q->where('age_restriction', $operation, $ageRestriction), 
            'releasedDetails:id,movie_id,coming_soon_movie_id'
        ])
            ->where('created_at', '<=', Carbon::now()->addMonth())
            ->orderByDesc('created_at')
            ->get();

        $notifications = $notifications->filter(fn ($notification) => $notification->movie);

        return !$notifications->count() ? $this->noContent(): $this->success($notifications);
    }
}
