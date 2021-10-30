<?php

namespace App\Http\Controllers\Api\Movie;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\RecentlyWatchedMovie\Request;
use App\Http\Requests\Movie\RecentlyWatchedMovie\UpdatePositionMillisRequest;
use App\Models\RecentlyWatchedMovie;
use Carbon\Carbon;

class RecentlyWatchedMoviesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = RecentlyWatchedMovie::with('movie')
            ->orderByDesc('recently_watched_at')
            ->get();

        return !$result
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\RecentlyWatchedMovie\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id)
    {
        $request->user('api')
            ->recentlyWatchedMovies()
            ->updateOrCreate(
                [
                    'user_profile_id' => $id,
                    'movie_id' => $request->movie_id
                ],
                [
                    'recently_watched_at' => Carbon::now(),
                    'duration_in_millis' => $request->duration_in_millis
                ]
            );

        return $this->success(null, 'Recently Watched Movie created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  RecentlyWatchedMovie  $recentlyWatchedMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(RecentlyWatchedMovie $recentlyWatchedMovie)
    {
        return $this->success($recentlyWatchedMovie);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\RecentlyWatchedMovie\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        RecentlyWatchedMovie::where([
            [ 'user_profile_id', $request->user_profile_id ],
            [ 'movie_id', $request->movie_id ],
            [ 'user_id', $request->user('api')->id ]
        ])
            ->update([
                'recently_watched_at' => Carbon::now()
            ]);
        
        return $this->success(null, 'Recently Watched Movie updated successfully.');
    }

    public function updatePositionMillis(UpdatePositionMillisRequest $request)
    {
        RecentlyWatchedMovie::where([
            [ 'user_profile_id', $request->user_profile_id ],
            [ 'movie_id', $request->movie_id ],
            [ 'user_id', $request->user('api')->id ]
        ])
            ->update([
                'last_played_position_millis' => $request->last_played_position_millis
            ]);

        return $this->success(null, 'Recently Watched Movie updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\Movie\RecentlyWatchedMovie\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        RecentlyWatchedMovie::where([
            [ 'user_profile_id', $request->user_profile_id ],
            [ 'movie_id', $request->movie_id ],
            [ 'user_id', $request->user('api')->id ]
        ])->delete();

        return $this->success(null, 'Recently Watched Movie deleted successfully.');
    }

    /**
     * Remove multiple resource from storage.
     *
     * @param  App\Http\Requests\Movie\RecentlyWatchedMovie\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear(Request $request)
    {
        RecentlyWatchedMovie::where([
            [ 'user_profile_id', $request->user_profile_id ],
            [ 'user_id', $request->user('api')->id ]
        ])->delete();

        return $this->success(null, 'Recently Watched Movies deleted successfully.');
    }
}
