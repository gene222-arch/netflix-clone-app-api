<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Director;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;

class DirectorsController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = true;

        return !$result
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->success([
            'data' => ''
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  Director  $director
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Director $director)
    {
        $director->update([

        ]);
        
        return $this->success([
            'data' => ''
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        return $this->success([
            'data' => ''
        ]);
    }
}
