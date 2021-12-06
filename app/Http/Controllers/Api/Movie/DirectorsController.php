<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Director;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Movie\Director\Request;
use App\Http\Requests\Upload\UploadAvatarRequest;
use App\Http\Requests\Movie\Director\DestroyRequest;
use App\Traits\ActivityLogsServices;

class DirectorsController extends Controller
{
    use HasUploadable, ActivityLogsServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Directors']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Director::orderBy('birth_name', 'ASC')->get();

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\Director\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use($request)
            {   
                $id = Director::create($request->validated())->id;

                $this->createLog(
                    'Create',
                    Director::class,
                    "video-management/directors/$id/update-director"
                );
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Director created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  Director  $director
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Director $director)
    {
        return $this->success($director);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\Director\Request  $request
     * @param  Director  $director
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Director $director)
    {
        try {
            DB::transaction(function () use($request, $director) 
            {
                $director->update($request->validated());

                $this->createLog(
                    'Update',
                    Director::class,
                    "video-management/directors/$director->id/update-director"
                );
            });
        } catch (\Throwable $th) {
           return $this->error($th->getMessage());
        }

        return $this->success(null, 'Director updated successfully.');
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
            'directors/avatars/',
            264, 
            406
        );
        
        return $this->success($path, 'Avatar uploaded successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  Director  $director
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEnabledStatus(Director $director)
    {
        try {
            DB::transaction(function () use($director)
            {
                $director->update([ 'enabled' => !$director->enabled ]);

                $this->createLog(
                    "Update",
                    Director::class,
                    "video-management/directors/$director->id/update-director"
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        try {
            DB::transaction(function () use($request)
            {
                Director::whereIn('id', $request->ids)->delete();

                $this->createLog("Delete", Director::class);
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Director/s deleted successfully.');
    }
}
