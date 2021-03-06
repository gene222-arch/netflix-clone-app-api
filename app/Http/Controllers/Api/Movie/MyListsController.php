<?php

namespace App\Http\Controllers\Api\Movie;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\MyList\Request;


class MyListsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Store a newly created resource in storage or delete if it exists.
     *
     * @param  App\Http\Requests\Movie\MyList\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request)
    {
        $authUser = $request->user('api');

        $movie = $authUser
            ->findProfileMyList($request->user_profile_id)
            ->where('movie_id', $request->movie_id);

        if ($movie->exists() && $movie->delete()) 
        {
            return $this->success(null, 'Removed to My List.');
        }

        $authUser->myLists()->create($request->validated());

        return $this->success(null, 'Added to My List.');
    }
}
