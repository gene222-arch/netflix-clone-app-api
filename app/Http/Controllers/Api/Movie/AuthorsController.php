<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\Author;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Movie\Author\Request;
use App\Http\Requests\Upload\UploadAvatarRequest;
use App\Http\Requests\Movie\Author\DestroyRequest;
use App\Http\Requests\Movie\Author\RestoreRequest;
use App\Traits\ActivityLogsServices;

class AuthorsController extends Controller
{
    use HasUploadable, ActivityLogsServices;

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
        $result = Author::orderBy('birth_name', 'ASC')->get();

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
        try {
            DB::transaction(function () use ($request) 
            {
                $id = Author::create($request->validated())->id;
                $this->createLog(
                    'Create',
                    Author::class,
                    "video-management/authors/$id/update-author"
                );
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

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
        try {
            DB::transaction(function () use ($request, $author) 
            {
                $author->update($request->validated());
                $this->createLog(
                    'Update',
                    Author::class,
                    "video-management/authors/$author->id/update-author"
                );
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

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
        try {
            DB::transaction(function () use ($author) 
            {
                $author->update([ 'enabled' => !$author->enabled ]);
                $this->createLog(
                    "Update",
                    Author::class,
                    "video-management/authors/$author->id/update-author"
                );
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
        
        return $this->success(null, 'Updated enabled successfully.');
    }

    public function restore(RestoreRequest $request)
    {
        Author::withTrashed()
            ->whereIn('id', $request->ids)
            ->restore();

        return $this->success(NULL, 'Selected authors are restored successfully');
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
     * @param \App\Http\Requests\Movie\Author\DestroyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        try {
            DB::transaction(function () use ($request) 
            {
                Author::whereIn('id', $request->ids)->delete();
                $this->createLog("Delete", Author::class);
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Author/s deleted successfully.');
    }
}
