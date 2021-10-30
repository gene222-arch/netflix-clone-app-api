<?php

namespace App\Http\Controllers\Api\Movie;

use App\Http\Controllers\Controller;
use App\Models\ReleasedMovie;
use Carbon\Carbon;

class ReleasedMovieNotifiedUsersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ReleasedMovie\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReleasedMovie $releasedMovie)
    {
        $releasedMovie
            ->releasedMovieNotifiedUsers()
            ->create([
                'user_id' => auth('api')->user()->id,
                'notified_at' => Carbon::now(),
                'ip_address' => request()->ip()
            ]);

        return $this->success(NULL, 'Added successfully');
    }
}
