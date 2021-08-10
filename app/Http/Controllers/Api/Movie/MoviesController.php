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
use App\Traits\Movie\HasMovieCRUD;
use App\Traits\Upload\HasUploadable;

class MoviesController extends Controller
{
    use ApiResponser, HasMovieCRUD, HasUploadable;

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:Super Administrator', 'permission:Manage Movies']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Movie::all();

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
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadPosterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPoster(UploadPosterRequest $request)
    {   
        $poster = $this->upload($request, 'poster', Movie::$FILE_PATH);

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
        $wallpaper = $this->upload($request, 'wallpaper', Movie::$FILE_PATH);
        
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
        $title_logo = $this->upload($request, 'title_logo', Movie::$FILE_PATH);
        
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
        $video = $this->upload($request, 'video', Movie::$FILE_PATH);
        
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
