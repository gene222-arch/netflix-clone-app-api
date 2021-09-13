<?php

namespace App\Http\Controllers\Api\AccessRight;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccessRight\AssignRoleToUsersRequest;
use App\Http\Requests\AccessRight\DestroyRequest;
use App\Http\Requests\AccessRight\StoreRequest;
use App\Http\Requests\AccessRight\UpdateRequest;
use App\Traits\AccessRight\AccessRightServices;
use App\Traits\Api\ApiResponser;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessRightsController extends Controller
{
    use ApiResponser, AccessRightServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:Super Administrator|super admin']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\AccessRight\AssignRoleToUsersRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(AssignRoleToUsersRequest $request, Role $role)
    {
        $this->assignRole($role, $request->user_ids);

        return $this->success(null, 'Role assigned successfully.');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Role::orderBy('created_at')->with('users')->get(['id', 'name']);

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
        $this->createAccessRight(
            $request->role,
            $request->permissions
        );

        return $this->success(null, 'Access Right created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Role  $role
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
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $this->updateAccessRight(
            $role,
            $request->role_name,
            $request->permissions
        );

        return $this->success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        Role::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Role\s deleted successfully.');
    }

}
