<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Cast;
use App\Models\Movie;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\ActivityLogsServices;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Movie\Cast\Request;
use App\Http\Requests\Movie\Cast\DestroyRequest;
use App\Http\Requests\Upload\UploadAvatarRequest;

class CastsController extends Controller
{
    use ApiResponser, HasUploadable, ActivityLogsServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Casts']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Cast::all();

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\Cast\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) 
        {
            $id = Cast::create($request->validated())->id;
            $this->createLog(
                "Create",
                Cast::class,
                "http://localhost:3000/video-management/casts/$id/update-cast"
            );
        });

        return $this->success(null, 'Cast created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  Cast  $cast
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Cast $cast)
    {
        return $this->success($cast);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\Cast\Request  $request
     * @param  Cast  $cast
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Cast $cast)
    {
        DB::transaction(function () use ($request, $cast) 
        {
            $cast->update($request->validated());
            $this->createLog(
                "Update",
                Cast::class,
                "http://localhost:3000/video-management/casts/$cast->id/update-cast"
            );
        });

        return $this->success(null, 'Cast updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Cast  $cast
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEnabledStatus(Cast $cast)
    {
        DB::transaction(function () use ($cast) 
        {
            $cast->update([ 'enabled' => !$cast->enabled ]);
            $this->createLog(
                "Update",
                Cast::class,
                "http://localhost:3000/video-management/casts/$cast->id/update-cast"
            );
        });
        
        return $this->success(null, 'Updated enabled successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  pp\Http\Requests\Upload\UploadAvatarRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $path = $this->upload(
            $request,
            'avatar',
            'casts/avatars/',
            264, 
            406
        );
        
        return $this->success($path, 'Avatar uploaded successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        DB::transaction(function () use ($request) 
        {
            Cast::whereIn('id', $request->ids)->delete();
            $this->createLog("Delete", Cast::class);
        });

        return $this->success(null, 'Cast/s deleted successfully.');
    }
}
