<?php

namespace App\Http\Controllers\Api\Movie;

use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\RecentlyWatchedMovie\Request;
use App\Models\RecentlyWatchedMovie;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RecentlyWatchedMoviesController extends Controller
{
    use ApiResponser;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        UserProfile::find($request->user_profile_id)
            ->recentlyWatchedMovies()
            ->create([
                'user_id' => Auth::user()->id,
                'movie_id' => $request->movie_id
            ]);


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
     * @param  RecentlyWatchedMovie  $recentlyWatchedMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RecentlyWatchedMovie $recentlyWatchedMovie)
    {
        $recentlyWatchedMovie->update([
            'recently_watched_at' => Carbon::now()
        ]);
        
        return $this->success(null, 'Recently Watched Movie updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  RecentlyWatchedMovie  $recentlyWatchedMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RecentlyWatchedMovie $recentlyWatchedMovie)
    {
        $recentlyWatchedMovie->delete();

        return $this->success(null, 'Recently Watched Movie deleted successfully.');
    }
}
