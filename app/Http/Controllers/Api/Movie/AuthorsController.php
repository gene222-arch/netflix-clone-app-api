<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Author;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Movie\Author\Request;
use App\Http\Requests\Upload\UploadAvatarRequest;
use App\Http\Requests\Movie\Author\DestroyRequest;
use App\Traits\ActivityLogsServices;

class AuthorsController extends Controller
{
    use ApiResponser, HasUploadable, ActivityLogsServices;

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
        DB::transaction(function () use ($request) 
        {
            $id = Author::create($request->validated())->id;
            $this->createLog(
                'Create',
                Author::class,
                "http://localhost:3000/video-management/authors/$id/update-author"
            );
        });

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
        DB::transaction(function () use ($request, $author) 
        {
            $author->update($request->validated());
            $this->createLog(
                'Update',
                Author::class,
                "http://localhost:3000/video-management/authors/$author->id/update-author"
            );
        });

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
        DB::transaction(function () use ($author) 
        {
            $author->update([ 'enabled' => !$author->enabled ]);
            $this->createLog(
                "Update",
                Author::class,
                "http://localhost:3000/video-management/authors/$author->id/update-author"
            );
        });
        
        return $this->success(null, 'Updated enabled successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $path = $this->upload(
            $request,
            'avatar',
            'authors/avatars/',
            264, 
            406
        );
        
        return $this->success($path, 'Avatar uploaded successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        DB::transaction(function () use ($request) 
        {
            Author::whereIn('id', $request->ids)->delete();
            $this->createLog("Delete", Author::class);
        });

        return $this->success(null, 'Author/s deleted successfully.');
    }
}
