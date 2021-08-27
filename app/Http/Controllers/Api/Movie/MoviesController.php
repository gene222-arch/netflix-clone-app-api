<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Movie;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\Movie\DestroyRequest;
use App\Http\Requests\Movie\Movie\StoreRequest;
use App\Http\Requests\Movie\Movie\UpdateRequest;
use App\Http\Requests\Upload\UploadPosterRequest;
use App\Http\Requests\Upload\UploadTitleLogoRequest;
use App\Http\Requests\Upload\UploadVideoRequest;
use App\Http\Requests\Upload\UploadWallpaperRequest;
use App\Traits\Movie\HasMovieServices;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\DB;

class MoviesController extends Controller
{
    use ApiResponser, HasMovieServices, HasUploadable;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Movies'])
            ->except(
                'index',
                'categorizedMovies',
                'topSearches',
                'getLatestTwenty',
                'incrementViews',
                'mostLikedMovies'
            );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->getMovies(request()->input('isForKids'));

        return $this->success($result);
    }

    /**
     * Display a listing of the resource and calculate most liked movies using Bayesian Algorithm.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function mostLikedMovies()
    {
        $result = $this->getMostLikedMovies();

        return $this->success($result);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topSearches()
    {
        $query = Movie::query();
        $isForKids = request()->input('isForKids', false);

        $query->select('movies.*', 'coming_soon_movies.video_trailer_path', 'movie_reports.*');
        $query->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12));

        $result = $query
            ->leftJoin('movie_reports', 'movie_reports.movie_id', '=', 'movies.id')
            ->leftJoin('coming_soon_movies', 'coming_soon_movies.title', '=', 'movies.title')
            ->where('movie_reports.search_count', '>', 0)
            ->orderByDesc('movie_reports.search_count')
            ->take(42)
            ->get();

        return $this->success($result);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categorizedMovies()
    {
        $user = request()->user('api');
        $isForKids = request()->input('isForKids', false);

        return $this->success($this->getCategorizedMovies($user, $isForKids));
    }


    /**
     * Display a listing of the first twenty latest source.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestTwenty()
    {
        $result = Movie::latest()->take(20)->get();

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\Movie\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $result = $this->createMovie($request);

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Movie created successfully.');
    } 


    /**
     * Display the specified resource.
     *
     * @param  Movie  $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Movie $movie)
    {
        return $this->success($movie);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\Movie\UpdateRequest  $request
     * @param  Movie  $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Movie $movie)
    {
        $result = $this->updateMovie($request, $movie);

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Movie updated successfully.');
    }

    /**
     * Update the specified resource views field in storage.
     *
     * @param  Movie  $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function incrementViews(Movie $movie)
    {
        $reportExists = $movie->report;

        if ($reportExists) 
        {
            $movie
                ->report()
                ->update([
                    'views' => DB::raw('views + 1'),
                    'total_views_within_a_day' => DB::raw('total_views_within_a_day + 1'),
                    'total_views_within_a_week' => DB::raw('total_views_within_a_week + 1'),
                ]);

            return $this->success(null, 'Movie report updated successfully.');
        }
        
        $movie
            ->report()
            ->create([
                'views' => 1,
                'total_views_within_a_day' => 1,
                'total_views_within_a_week' => 1
            ]);

        return $this->success(null, 'Movie report created successfully.');
    }

    
    /**
     * Update the specified resource search count field in storage.
     *
     * @param  Movie  $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function incrementSearchCount(Movie $movie)
    {
        $reportExists = $movie->report;

        if ($reportExists) {
            $movie->report()->increment('search_count');

            return $this->success(null, 'Movie report updated successfully.');
        }
        
        $movie->report()->create([ 'search_count' => 1 ]);

        return $this->success(null, 'Movie report created successfully.');
    }

    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadPosterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPoster(UploadPosterRequest $request)
    {   
        $poster = $this->upload(
            $request, 
            'poster', 
            Movie::$FILE_PATH, 
            264, 
            406
        );

        return $this->success($poster);
    }

    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadTitleLogoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadWallpaper(UploadWallpaperRequest $request)
    {
        $wallpaper = $this->upload(
            $request, 
            'wallpaper', 
            Movie::$FILE_PATH,
            521,
            293
        );
            
        return $this->success($wallpaper);
    }

    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadVideoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTitleLogo(UploadTitleLogoRequest $request)
    {
        $title_logo = $this->upload(
            $request, 
            'title_logo', 
            Movie::$FILE_PATH, 
            706, 
            135
        );
        
        return $this->success($title_logo);
    }

    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadWallpaperRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadVideo(UploadVideoRequest $request)
    {
        $video = $this->videoUpload($request, 'video', Movie::$FILE_PATH);
        
        return $this->success($video);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\Movie\Movie\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        Movie::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Movie/s deleted successfully.');
    }
}
