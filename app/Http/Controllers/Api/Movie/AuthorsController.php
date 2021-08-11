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

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Authors']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Author::all();

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
     * @param  Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Author $author)
    {
        return $this->success($author);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Movie\Author\Request  $request
     * @param  Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Author $author)
    {
        $author->update($request->validated());

        return $this->success(null, 'Author updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEnabledStatus(Author $author)
    {
        $author->update([
            'enabled' => !$author->enabled 
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
        Author::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Author/s deleted successfully.');
    }
}
