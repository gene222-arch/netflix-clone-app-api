<?php

namespace App\Http\Controllers\Api\Movie;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\MyDownload\DestroyRequest;
use App\Http\Requests\Movie\MyDownload\StoreRequest;
use App\Models\MyDownload;
use App\Traits\Api\ApiResponser;

class MyDownloadsController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = MyDownload::all();

        return !$result
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Movie\MyDownload\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $request->user('api')->myDownloads()->create($request->validated());

        return $this->success(null, 'Downloaded successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  MyDownload  $myDownload
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(MyDownload $myDownload)
    {
        return $this->success($myDownload);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\Movie\MyDownload\DestroyRequest  $request
     * @param integer  $userProfileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request, int $userProfileId)
    {
        $request->user('api')
            ->findDownloadsByProfileId($userProfileId)
            ->delete($request->ids);

        return $this->success(null, 'Downloads deleted successfully.');
    }
}
