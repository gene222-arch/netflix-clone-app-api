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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Cast::all([
            'id',
            'pseudonym',
            'birth_name',
            'date_of_birth',
            'biographical_information'
        ]);

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
