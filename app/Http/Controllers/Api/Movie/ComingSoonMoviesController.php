<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Trailer;
use App\Models\ComingSoonMovie;
use App\Http\Controllers\Controller;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Upload\UploadVideoRequest;
use App\Traits\Movie\HasComingSoonMovieServices;
use App\Http\Requests\Upload\UploadPosterRequest;
use App\Http\Requests\Upload\UploadTitleLogoRequest;
use App\Http\Requests\Upload\UploadWallpaperRequest;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\DestroyRequest;
use App\Http\Requests\Movie\ComingSoonMovie\ReleaseRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerStoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerUpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerDestroyRequest;
use App\Traits\ActivityLogsServices;

class ComingSoonMoviesController extends Controller
{
    use HasComingSoonMovieServices, HasUploadable, ActivityLogsServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Coming Soon Movies'])
            ->except('index', 'incrementViews', 'notifyUserViaMobileOnMovieReleased');
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
            request()->input('isComingSoon', false),
            request()->input('isFiltered', false)
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
       $result = $this->createTrailer($comingSoonMovie, $request->validated());

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Trailer created successfully.');
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
     * @param  App\Http\Requests\Movie\ComingSoonMovie\ReleaseRequest  $request
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function releaseMovie(ReleaseRequest $request, ComingSoonMovie $comingSoonMovie)
    {
        $result = $this->release($request, $comingSoonMovie);

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Status updated successfully.');
    }

    public function notifyUserViaMobileOnMovieReleased(ComingSoonMovie $comingSoonMovie)
    {
        $authUser = request()->user('api');

        $shouldRemindUser = $authUser
            ->remindMes()
            ->where('coming_soon_movie_id', '=', $comingSoonMovie->id)
            ->exists();

        $authUser
            ->notify(
                new \App\Notifications\MovieReleaseExpoNotification(
                    $comingSoonMovie->title, 
                    $shouldRemindUser
                )
            );

        return $this->success();
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
        $result = $this->trailerUpdate(
            $comingSoonMovie,
            $trailer->id,
            $request->validated()
        );

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Trailer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        $result = $this->deleteManyComingSoonMovies($request->ids);

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Coming Soon Movie/s deleted successfully.');
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
        $result = $this->deleteManyTrailers($comingSoonMovie, $request->ids);

        if ($result) {
            ComingSoonMovie::cacheToForget();
        }

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Trailer/s deleted successfully.');
    }
}
