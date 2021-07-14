<?php

namespace App\Http\Controllers\Api\ComingSoonMovie;

use App\Models\ComingSoonMovie;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Traits\ComingSoonMovie\HasComingSoonMovieCRUD;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\DestroyRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerDestroyRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerStoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerUpdateRequest;
use App\Models\Trailer;

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
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\TrailerStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTrailer(TrailerStoreRequest $request)
    {
       Trailer::create($request->validated());

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
        return $this->success($comingSoonMovie->with('trailers')->get());
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
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\ComingSoonMovie\TrailerUpdateRequest  $request
     * @param  Trailer  $trailer
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTrailer(TrailerUpdateRequest $request, Trailer $trailer)
    {
        $trailer->update($request->validated());

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
