<?php

namespace App\Http\Controllers\Api\Movie;

use Carbon\Carbon;
use App\Models\Trailer;
use App\Models\ComingSoonMovie;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Traits\Movie\HasComingSoonMovieCRUD;
use App\Http\Requests\Upload\UploadVideoRequest;
use App\Http\Requests\Upload\UploadPosterRequest;
use App\Http\Requests\Upload\UploadTitleLogoRequest;
use App\Http\Requests\Upload\UploadWallpaperRequest;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\DestroyRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerStoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerUpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerDestroyRequest;
use App\Traits\Upload\HasUploadable;

class ComingSoonMoviesController extends Controller
{
    use ApiResponser, HasComingSoonMovieCRUD, HasUploadable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = ComingSoonMovie::all();

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTrailer(TrailerStoreRequest $request)
    {
       Trailer::create($request->validated);

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
        return $this->success($comingSoonMovie);
    }


    /**
     * Display the specified resource.
     *
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTrailer(ComingSoonMovie $comingSoonMovie)
    {
        return $this->success($comingSoonMovie->trailers()->get());
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
     * ! NOT TESTED
     */

        /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadPosterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPoster(UploadPosterRequest $request)
    {   
        $poster = $this->upload($request, 'poster', ComingSoonMovie::pathToStore($request->title));

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
        $wallpaper = $this->upload($request, 'wallpaper', ComingSoonMovie::pathToStore($request->title));
        
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
        $title_logo = $this->upload($request, 'title_logo', ComingSoonMovie::pathToStore($request->title));
        
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
        $videoTrailer = $this->upload($request, 'video_trailer', ComingSoonMovie::pathToStore($request->title));
        
        return $this->success($videoTrailer);
    }

    /**
     * !
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(ComingSoonMovie $comingSoonMovie)
    {
        $comingSoonMovie->update([
            'status' => $comingSoonMovie->status === 'Released' ? 'Coming Soon' : 'Released',
            'released_at' => $comingSoonMovie->status === 'Released' ? null : Carbon::now()
        ]);

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
        $this->trailerUpdate($request, $comingSoonMovie, $trailer);

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
     * @param  integer  $comingSoonMovieID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTrailer(TrailerDestroyRequest $request, int $comingSoonMovieID)
    {
        Trailer::where('coming_soon_movie_id', $comingSoonMovieID)->whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Trailer/s deleted successfully.');
    }
}
