<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Movie;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\Movie\DestroyRequest;
use App\Http\Requests\Movie\Movie\StoreRequest;
use App\Http\Requests\Movie\Movie\UpdateRequest;
use App\Traits\Movie\Movie\HasMovieCRUD;

class MoviesController extends Controller
{
    use ApiResponser, HasMovieCRUD;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Movie::all([
            'id',
            'genres',
            'year_of_release',
            'language',
            'plot'
        ]);

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
