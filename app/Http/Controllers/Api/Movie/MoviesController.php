<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\ActivityLogsServices;
use App\Traits\Upload\HasUploadable;
use App\Traits\Movie\HasMovieServices;
use App\Http\Requests\Movie\Movie\StoreRequest;
use App\Http\Requests\Movie\Movie\UpdateRequest;
use App\Http\Requests\Upload\UploadVideoRequest;
use App\Http\Requests\Movie\Movie\DestroyRequest;
use App\Http\Requests\Movie\Movie\RestoreRequest;
use App\Http\Requests\Upload\UploadPosterRequest;
use App\Http\Requests\Upload\UploadTitleLogoRequest;
use App\Http\Requests\Upload\UploadWallpaperRequest;
use App\Http\Requests\Upload\UploadVideoPreviewRequest;

class MoviesController extends Controller
{
    use HasMovieServices, HasUploadable, ActivityLogsServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Movies'])
            ->except(
                'index',
                'categorizedMovies',
                'topSearches',
                'getLatestTwenty',
                'incrementViews',
                'mostLikedMovies',
                'incrementSearchCount',
                'showRandom'
            );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $isForKids = request()->input('isForKids', false);
        $trashedOnly = request()->input('trashedOnly', false);

        $result = $this->getMovies($isForKids, $trashedOnly);

        return $this->success($result ?? []);
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
        $result = $this->getTopSearches(request()->input('isForKids', false));

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
        $result = $this->getLatestTwentyMovies();

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Movie\Movie\StoreRequest  $request
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
     * Display a randomized resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showRandom()
    {
        $isForKids = (bool) request()->input('isForKids');

        $movie = Movie::query()
            ->when($isForKids, fn ($q) => $q->where('age_restriction', '<=', 12))
            ->get()
            ->random(1)
            ->first();

        return $this->success($movie);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Movie\Movie\UpdateRequest  $request
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

    public function restore(RestoreRequest $request)
    {
        Movie::withTrashed()
            ->whereIn('id', $request->ids)
            ->restore();

        Movie::cacheToForget();
        
        return $this->success(NULL, 'Selected movies are restored successfully');
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
            'movies/posters/', 
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
            'movies/wallpapers/', 
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
            'movies/title-logos/', 
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
        $video = $this->videoUpload(
            $request, 
            'video',
        'movies/videos', 
    );
        
        return $this->success($video);
    }

    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadVideoPreviewRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadVideoPreview(UploadVideoPreviewRequest $request)
    {
        $video = $this->videoUpload(
            $request, 
            'video_preview', 
            'movies/video-previews'
        );
        
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
        try {
            DB::transaction(function () use($request)
            {
                Movie::whereIn('id', $request->ids)->delete();

                $this->createLog('Delete', Movie::class);

                Movie::cacheToForget();
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Movie/s deleted successfully.');
    }
}
