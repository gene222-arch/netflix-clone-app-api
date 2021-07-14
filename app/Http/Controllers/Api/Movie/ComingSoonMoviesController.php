<?php

namespace App\Http\Controllers\Api\ComingSoonMovie;

use App\Models\ComingSoonMovie;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Traits\ComingSoonMovie\HasComingSoonMovieCRUD;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\DestroyRequest;

class ComingSoonMoviesController extends Controller
{
    use ApiResponser, HasComingSoonMovieCRUD;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = ComingSoonMovie::all([
            'id',
            'genres',
            'language',
            'plot',
            'status'
        ]);

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
        $this->createComingSoonMovie($request);

        return $this->success(null, 'ComingSoonMovie created successfully.');
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
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest  $request
     * @param  ComingSoonMovie  $comingSoonMovie
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, ComingSoonMovie $comingSoonMovie)
    {
        $this->updateComingSoonMovie($request, $comingSoonMovie);

        return $this->success(null, 'Coming Soon Movie updated successfully.');
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
}
