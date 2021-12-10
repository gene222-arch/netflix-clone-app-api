<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\ActivityLogsServices;
use App\Http\Requests\Movie\Genre\StoreRequest;
use App\Http\Requests\Movie\Genre\UpdateRequest;
use App\Http\Requests\Movie\Genre\DestroyRequest;
use App\Http\Requests\Movie\Genre\RestoreRequest;

class GenresController extends Controller
{
    use ActivityLogsServices;

    
    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Genres']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $trashedOnly = request()->input('trashedOnly', false);

        if ($trashedOnly === 'false') {
            $result = Genre::orderBy('name', 'ASC')->get();
        }

        if ($trashedOnly === 'true') {
            $result = Genre::onlyTrashed()
                ->orderBy('name', 'ASC')
                ->get();
        }

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
            return $this->error($th->getMessage());
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

    public function restore(RestoreRequest $request)
    {
        Genre::withTrashed()
            ->whereIn('id', $request->ids)
            ->restore();

        return $this->success(NULL, 'Selected genres are restored successfully');
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
