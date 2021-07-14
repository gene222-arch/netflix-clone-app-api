<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Cast;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;

class CastsController extends Controller
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
     * @param  Cast  $cast
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Cast $cast)
    {
        return !$this->success($cast);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Cast  $cast
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Cast $cast)
    {
        $cast->update([

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
