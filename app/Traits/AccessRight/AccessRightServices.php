<?php

namespace App\Traits\AccessRight;

use App\Traits\ActivityLogsServices;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

trait AccessRightServices
{
    use ActivityLogsServices;

    public function assignRole(Role $role, array $userIds)
    {
        try {
            DB::transaction(function () use($role, $userIds)
            {
                $previousAssignedUsers = $role->users->map->pivot->map->model_id;
                
                if ($previousAssignedUsers->count()) {
                    $role->users()->detach($previousAssignedUsers);
                }

                $role->users()->attach($userIds);

                $this->createLog(
                    "Assign",
                    Role::class,
                    "http://localhost:3000/access-rights/$role->id/update"
                );
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    

    /**
     * Create a new role and assign a list of permissions
     * 
     * @param string $role
     * @param array $permissions
     * @return mixed
     */
    public function createAccessRight (string $role, array $permissions): mixed
    {
        try {

            DB::transaction(function () use ($role, $permissions) 
            {
                $role = Role::create([
                    'name' => $role,
                    'guard_name' => 'api',
                    'updated_at' => null
                ]);
        
                $role->givePermissionTo(
                    array_merge(
                        $permissions,
                        [
                            'View Dashboard'
                        ]
                    )
                );

                $this->createLog(
                    "Create",
                    Role::class,
                    "http://localhost:3000/access-rights/$role->id/update"
                );
            });

            return true;

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Update an existing access right
     *
     * @param  Role $previousRole
     * @param  string $roleName
     * @param  array $permissions
     * @return mixed
     */
    public function updateAccessRight (Role $previousRole, string $roleName, array $permissions): mixed
    {
        try {
            DB::transaction(function () use ($previousRole, $roleName, $permissions) 
            {
                $previousRole->update([ 'name' => $roleName ]);

                $previousRole->syncPermissions($permissions);

                $this->createLog(
                    "Update",
                    Role::class,
                    "http://localhost:3000/access-rights/$previousRole->id/update"
                );
            });

        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }

}