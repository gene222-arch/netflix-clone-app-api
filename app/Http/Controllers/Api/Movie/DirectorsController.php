<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Director;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\Director\Request;
use App\Http\Requests\Movie\Director\DestroyRequest;

class DirectorsController extends Controller
{
    use ApiResponser;

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
        $result = Director::all();

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
        Director::create($request->validated());

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
        $director->update($request->validated());

        return $this->success(null, 'Director updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Director  $director
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEnabledStatus(Director $director)
    {
        $director->update([
            'enabled' => !$director->enabled 
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
        Director::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Director/s deleted successfully.');
    }
}
