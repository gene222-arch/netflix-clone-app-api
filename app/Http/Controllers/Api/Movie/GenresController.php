<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;

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
        $result = true;

        return !$result
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->success([
            'data' => ''
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  Genre  $genre
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Genre $genre)
    {
        $genre->update([

        ]);
        
        return $this->success([
            'data' => ''
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        return $this->success([
            'data' => ''
        ]);
    }
}
