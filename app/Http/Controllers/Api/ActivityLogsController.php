<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityLog\DestroyRequest;
use App\Http\Requests\ActivityLog\StoreRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;

class ActivityLogsController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = ActivityLog::with('createdBy')->get();

        return !$result
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ActivityLog\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        ActivityLog::create([
            'type' => 'Create',
            'model_type' => ActivityLog::class,
            'user_id' => $request->user('api')->id,
            'description' => $request->description
        ]);

        return $this->success(null, 'Activity Log created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  ActivityLog  $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ActivityLog $activityLog)
    {
        return $this->success($activityLog);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\ActivityLog\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        ActivityLog::findMany($request->ids)->delete();

        return $this->success(null, 'Activity Log/s deleted successfully.');
    }
}
