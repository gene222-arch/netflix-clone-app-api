<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Cast;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\Cast\DestroyRequest;
use App\Http\Requests\Movie\Cast\Request;
use App\Models\Movie;

class CastsController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:Super Administrator', 'permission:Manage Casts']);
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
        Cast::create($request->validated());

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
        $cast->update($request->validated());

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
        $cast->update([
            'enabled' => !$cast->enabled 
        ]);
        
        return $this->success(null, 'Updated enabled successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        Cast::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Cast/s deleted successfully.');
    }
}
