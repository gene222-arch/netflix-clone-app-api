<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfile\StoreRequest;
use App\Http\Requests\UserProfile\UpdateRequest;
use App\Models\UserProfile;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\Auth;

class UserProfilesController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the user's profiles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $profiles = Auth::user()->profiles;

        return !$profiles
            ? $this->noContent()
            : $this->success($profiles, 'User`s profiles fetched successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $profile_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserProfile $profile)
    {
        return !$profile ? $this->noContent() : $this->success($profile);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        Auth::user()->profiles()->create($request->validated());

        return $this->success(null, 'Profile created successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  UserProfile  $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, UserProfile $profile)
    {
        $profile->update($request->validated());

        return $this->success(null, 'Profile updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserProfile $profile)
    {
        $profile->delete();

        return $this->success(null, 'Profile deleted successfully');
    }

}