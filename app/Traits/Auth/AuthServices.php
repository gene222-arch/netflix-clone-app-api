<?php

namespace App\Traits\Auth;

use App\Models\User;

trait AuthServices
{
    public function authPermissionViaRoles (User $user = null)
    {
        return ! $user 
            ? auth()->user()->getPermissionsViaRoles()->map->name
            : $user->roles->first()->permissions->map->name;
    }
}