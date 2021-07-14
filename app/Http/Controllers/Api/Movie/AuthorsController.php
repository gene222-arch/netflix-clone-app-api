<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Author;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\Author\Request;
use App\Http\Requests\Movie\Author\DestroyRequest;

class AuthorsController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Author::all([
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
     * @param  App\Http\Requests\Movie\Author\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Author::create($request->validated());

        return $this->success(null, 'Author created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  Author  $cast
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Author $cast)
    {
        return $this->success($cast);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\Author\Request  $request
     * @param  Author  $cast
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Author $cast)
    {
        $cast->update($request->validated());

        return $this->success(null, 'Author updated successfully.');
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        Author::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Author/s deleted successfully.');
    }
}
