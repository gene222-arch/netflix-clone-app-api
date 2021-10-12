<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

trait HasEmployeeServices
{

    public static function store(\App\Http\Requests\Employee\StoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) 
            {
                Employee::create($request->validated());

                /** Create employee user account */
                $userDetails = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make(Str::random(10)),
                    'avatar_path' => $request->avatar_path
                ];

                $user = User::query()->create($userDetails);
                $user->sendQueueEmailVerificationNotification();
                $user->assignRole($request->role_id);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }

    public static function update(\App\Http\Requests\Employee\UpdateRequest $request, \App\Models\Employee $employee)
    {
        try {
            DB::transaction(function () use ($request, $employee) 
            {
                $employee->update($request->validated());

                /** Update employee user account */
                $user = User::query()->firstWhere('email', '=', $employee->email);

                $userDetails = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'avatar_path' => $request->avatar_path
                ];

                if ($request->email !== $employee->email) 
                {
                    $userDetails = $userDetails + [
                        'email_verified_at' => NULL
                    ];

                    $user->sendQueueEmailVerificationNotification();
                }

                $user->update($userDetails);
                $user->syncRoles($request->role_id);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public static function destory(\App\Http\Requests\Employee\DestroyRequest  $request)
    {
        try {
            DB::transaction(function () use($request) 
            {
                $employeeEmails = Employee::whereIn('id', $request->ids)
                    ->get()
                    ->map
                    ->email
                    ->toArray();

                DB::table('users')->whereIn('email', $employeeEmails)->delete();
                
                Employee::whereIn('id', $request->ids)->delete();
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }

}