<?php

namespace App\Http\Controllers\Api\AccessRight;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\ActivityLogsServices;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\AccessRight\StoreRequest;
use App\Traits\AccessRight\AccessRightServices;
use App\Http\Requests\AccessRight\UpdateRequest;
use App\Http\Requests\AccessRight\DestroyRequest;
use App\Http\Requests\AccessRight\AssignRoleToUsersRequest;
use Spatie\Permission\Models\Role;

class AccessRightsController extends Controller
{
    use AccessRightServices, ActivityLogsServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Access Rights']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AccessRight\AssignRoleToUsersRequest  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(AssignRoleToUsersRequest $request, Role $role)
    {
        $result = $this->assignRole($role, $request->ids);

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Role assigned successfully.');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Role::with('users')
            ->withCount('users')
            ->withCount('permissions')
            ->orderBy('created_at')
            ->get(['id', 'name']);

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions()
    {
        $result = Permission::orderBy('name')->get(['id', 'name', 'description']);

        return !$result->count()
            ? $this->noContent()
            : $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $result = $this->createAccessRight(
            $request->role,
            $request->permissions
        );

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Access Right created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return !$role
            ? $this->noContent()
            : $this->success([
                'role' => $role->name,
                'permissions' => $role->permissions
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $result = $this->updateAccessRight(
            $role,
            $request->role,
            $request->permissions
        );

        return $result !== true 
            ? $this->error($result)
            : $this->success(null, 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        try {
            DB::transaction(function ($request) 
            {
                Role::whereIn('id', $request->ids)->delete();    

                $this->createLog("Delete", Role::class);
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Role\s deleted successfully.');
    }

}
