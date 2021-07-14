<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\Genre\DestroyRequest;
use App\Http\Requests\Movie\Genre\StoreRequest;
use App\Http\Requests\Movie\Genre\UpdateRequest;

class GenresController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Genre::all();

        return !$result
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\Genre\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        Genre::create($request->validated());

        return $this->success(null, 'Genre created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Genre  $genre
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Genre $genre)
    {
        return $this->success($genre);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\Genre\UpdateRequest  $request
     * @param  Genre  $genre
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Genre $genre)
    {
        $genre->update($request->validated());

        return $this->success(null, 'Genre updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param App\Http\Requests\Movie\Genre\DestroyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        Genre::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Genre/s deleted successfully.');
    }
}
