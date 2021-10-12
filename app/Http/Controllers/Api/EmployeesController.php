<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Traits\Api\ApiResponser;
use App\Traits\HasEmployeeServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Http\Requests\Employee\DestroyRequest;
use App\Http\Requests\Upload\UploadAvatarRequest;
use App\Models\User;
use App\Traits\Upload\HasUploadable;

class EmployeesController extends Controller
{
    use ApiResponser, HasEmployeeServices, HasUploadable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Employee::with('roles')
            ->withCount('roles')
            ->having('roles_count', '<=', 1)
            ->orderByDesc('created_at')
            ->get();

        return !$result
            ? $this->noContent()
            : $this->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Employee\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $result = HasEmployeeServices::store($request);

        return is_string($result)
            ? $this->error($result)
            : $this->success(null, 'Employee created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Employee $employee)
    {
        $role = User::firstWhere('email', $employee->email)->roles->first()->name;

        return $this->success([
            'employee' => $employee,
            'role' => $role
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Employee\UpdateRequest  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Employee $employee)
    {
        $result = HasEmployeeServices::update($request, $employee);
        
        return is_string($result)
            ? $this->error($result)
            : $this->success(null, 'Employee updated successfully');
    }


    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify()
    {
        $hashedEmail = request()->input('hash');
        $id = request()->input('id');

        $userAccount = User::query()->findOrFail($id);

        if (! (request()->has('hash') && request()->has('id'))) {
            return $this->error('You do not have the right to be verified.');
        }

        if ($userAccount->hasVerifiedEmail()) {
            return $this->success(null, 'Email is already verified');
        }

        if (hash_equals((string) $hashedEmail, sha1($userAccount->email))) 
        {
            $userAccount->markEmailAsVerified();

            return $this->success(null, 'Email verified successfully');
        }

        return $this->error('Email not verified');
    }


    /**
     * Upload a file.
     *
     * @param  App\Http\Requests\Upload\UploadAvatarRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(UploadAvatarRequest $request)
    {   
        $avatar = $this->upload(
            $request, 
            'avatar', 
            'employees/avatars/', 
            264, 
            406
        );

        return $this->success($avatar);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Employee\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        $result = HasEmployeeServices::destory($request);

        return is_string($result)
            ? $this->error($result)
            : $this->success(null, 'Employee\s deleted successfully');
    }
}
