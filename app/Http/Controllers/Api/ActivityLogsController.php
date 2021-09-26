<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityLog\DestroyRequest;
use App\Http\Requests\ActivityLog\StoreUpdateRequest;
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
     * @param  App\Http\Requests\ActivityLog\StoreUpdateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUpdateRequest $request)
    {
        ActivityLog::create([
            'type' => $request->type,
            'model_type' => 'App\\Models\\' . $request->model_type,
            'description' => $request->description
        ]);

        return $this->success(null, 'Activity Log created successfully.');
    }


    /**
     * Update specified resource in storage.
     *
     * @param  App\Http\Requests\ActivityLog\StoreUpdateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreUpdateRequest $request, ActivityLog $activityLog)
    {
        $activityLog->update([
            'type' => $request->type,
            'model_type' => 'App\\Models\\' . $request->model_type,
            'description' => $request->description
        ]);

        return $this->success(null, 'Activity Log updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  ActivityLog  $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ActivityLog $activityLog)
    {
        return $this->success($activityLog->with('createdBy')->find($activityLog->id));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\ActivityLog\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        ActivityLog::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Activity Log/s deleted successfully.');
    }
}
