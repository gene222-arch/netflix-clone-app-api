<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\MovieNotification;
use App\Http\Controllers\Controller;
use App\Traits\Api\ApiResponser;
use Illuminate\Http\Request;

class MovieNotificationsController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = MovieNotification::with('movie:id,title,wallpaper_path')->get();

        return !$notifications->count() ? $this->noContent(): $this->success($notifications);
    }
}
