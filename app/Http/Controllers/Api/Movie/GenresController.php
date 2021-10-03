<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\Genre\StoreRequest;
use App\Http\Requests\Movie\Genre\UpdateRequest;
use App\Http\Requests\Movie\Genre\DestroyRequest;
use App\Traits\ActivityLogsServices;

class GenresController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Genres']);
    }

    use ApiResponser, ActivityLogsServices;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Genre::orderBy('name', 'asc')->get();

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
        try {
            DB::transaction(function () use ($request) 
            {
                $id = Genre::create($request->validated())->id;

                $this->createLog(
                    'Create',
                    Genre::class,
                    "video-management/genres/$id/update-genre"
                );
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

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
        try {
            DB::transaction(function () use ($request, $genre) 
            {
                $genre->update($request->validated());

                $this->createLog(
                    'Update',
                    Genre::class,
                    "video-management/genres/$genre->id/update-genre"
                );
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Genre updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Genre  $genre
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEnabledStatus(Genre $genre)
    {
        try {
            DB::transaction(function () use ($genre) 
            {
                $genre->update([ 'enabled' => !$genre->enabled ]);

                $this->createLog(
                    "Update",
                    Genre::class,
                    "video-management/genres/$genre->id/update-genre"
                );
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
        
        return $this->success(null, 'Updated enabled successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param App\Http\Requests\Movie\Genre\DestroyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        try {
            DB::transaction(function () use ($request) 
            {
                Genre::whereIn('id', $request->ids)->delete();

                $this->createLog("Delete", Genre::class);
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Genre/s deleted successfully.');
    }
}
