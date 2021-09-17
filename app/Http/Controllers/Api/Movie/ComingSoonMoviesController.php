<?php

namespace App\Http\Controllers\Api\Movie;

use Carbon\Carbon;
use App\Models\Movie;
use App\Models\Trailer;
use App\Models\ComingSoonMovie;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\Cache;
use App\Events\ComingSoonMovieReleasedEvent;
use App\Http\Requests\Upload\UploadVideoRequest;
use App\Traits\Movie\HasComingSoonMovieServices;
use App\Http\Requests\Upload\UploadPosterRequest;
use App\Http\Requests\Upload\UploadTitleLogoRequest;
use App\Http\Requests\Upload\UploadWallpaperRequest;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\DestroyRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerStoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateStatusRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerUpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerDestroyRequest;

class ComingSoonMoviesController extends Controller
{
    use ApiResponser, HasComingSoonMovieServices, HasUploadable;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Coming Soon Movies'])
            ->except('index', 'incrementViews');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->getComingSoonMovies(
            request()->input('isForKids', false),
            request()->input('status')
        );

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $result = $this->createComingSoonMovie($request);

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'ComingSoonMovie created successfully.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\TrailerStoreRequest  $request
     * @param ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTrailer(ComingSoonMovie $comingSoonMovie, TrailerStoreRequest $request)
    {
       $comingSoonMovie->trailers()->create($request->validated());

        return $this->success(null, 'Trailer created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ComingSoonMovie $comingSoonMovie)
    {
        return $this->success($comingSoonMovie->with('trailers')->find($comingSoonMovie->id));
    }


    /**
     * Display the specified resource.
     *
     * @param  ComingSoonMovie  $comingSoonMovie
     * @param  Trailer  $trailer
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTrailer(ComingSoonMovie $comingSoonMovie, Trailer $trailer)
    {
        return $this->success($trailer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest  $request
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, ComingSoonMovie $comingSoonMovie)
    {
        $result = $this->updateComingSoonMovie($request, $comingSoonMovie);

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Coming Soon Movie updated successfully.');
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
            'coming-soon-movies/posters/',
            264, 
            406
        );

        return $this->success($poster);
    }


    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadPosterRequest  $request
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTrailerPoster(ComingSoonMovie $comingSoonMovie, UploadPosterRequest $request)
    {   
        $poster = $this->upload(
            $request, 
            'poster', 
            'coming-soon-movies/trailers/posters/',
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
            'coming-soon-movies/wallpapers/',
            521,
            293
        );
        
        return $this->success($wallpaper);
    }

    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadTitleLogoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTrailerWallpaper(UploadWallpaperRequest $request)
    {
        $wallpaper = $this->upload(
            $request, 
            'wallpaper', 
            'coming-soon-movies/trailers/wallpapers/',
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
            'coming-soon-movies/title-logos/',
            706, 
            135
        );
        
        return $this->success($title_logo);
    }

    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadVideoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTrailerTitleLogo(UploadTitleLogoRequest $request)
    {
        $title_logo = $this->upload(
            $request, 
            'title_logo', 
            'coming-soon-movies/trailers/title-logos/',
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
            'coming-soon-movies/videos'
        );
        
        return $this->success($video);
    }


    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadWallpaperRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTrailerVideo(UploadVideoRequest $request)
    {
        $videoTrailer = $this->videoUpload(
            $request, 
            'video', 
            'coming-soon-movies/trailers/videos'
        );
        
        return $this->success($videoTrailer);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(UpdateStatusRequest $request, ComingSoonMovie $comingSoonMovie)
    {
        try {
            DB::transaction(function () use ($request, $comingSoonMovie) 
            {
                $currentDate = Carbon::today();

                $status = $comingSoonMovie->status === 'Released' ? 'Coming Soon' : 'Released';
                $releasedAt = $comingSoonMovie->status === 'Released' ? null : $currentDate;
        
                $comingSoonMovie->update([
                    'status' => $status,
                    'released_at' => $releasedAt
                ]);
        
                if ($status === 'Released') 
                {
                    $movie = array_merge(
                        $comingSoonMovie->toArray(),
                        [
                            'year_of_release' => $currentDate->format('Y'),
                            'date_of_release' => $currentDate,
                            'duration_in_minutes' => $request->duration_in_minutes,
                            'video_path' => $request->video_path,
                            'video_preview_path' => $comingSoonMovie->video_trailer_path,
                            'video_size_in_mb' => $request->video_size_in_mb
                        ]
                    );
        
                    $authorIds = $comingSoonMovie->authors()->get();
                    $castIds = $comingSoonMovie->casts()->get();
                    $directorIds = $comingSoonMovie->directors()->get();
                    $genreIds = $comingSoonMovie->genres()->get();
        
                    $movie = Movie::create($movie);
                    $movie->authors()->attach($authorIds);
                    $movie->casts()->attach($castIds);
                    $movie->directors()->attach($directorIds);
                    $movie->genres()->attach($genreIds);

                    event(new ComingSoonMovieReleasedEvent($comingSoonMovie));
                }
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Status updated successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\TrailerUpdateRequest  $request
     * @param  ComingSoonMovie  $comingSoonMovie
     * @param  Trailer  $trailer
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTrailer(TrailerUpdateRequest $request, ComingSoonMovie $comingSoonMovie, Trailer $trailer)
    {
        $comingSoonMovie
            ->trailers()
            ->find($trailer->id)
            ->update($request->validated());

        return $this->success(null, 'Trailer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        ComingSoonMovie::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Coming Soon Movie/s deleted successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\TrailerDestroyRequest  $request
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTrailer(TrailerDestroyRequest $request, ComingSoonMovie $comingSoonMovie)
    {
        $comingSoonMovie->trailers()->whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Trailer/s deleted successfully.');
    }
}
