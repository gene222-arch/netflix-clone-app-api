<?php

namespace App\Traits;

use App\Jobs\QueueEmployeeEmailVerificationNotification;
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

                $randomPassword = Str::random(10);

                /** Create employee user account */
                $userDetails = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($randomPassword),
                    'avatar_path' => $request->avatar_path
                ];

                $user = User::query()->create($userDetails);   
                $user->assignRole($request->role_id);

                dispatch(
                    new QueueEmployeeEmailVerificationNotification($user, $randomPassword)
                )->delay(5);

            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }

    public static function update(\App\Http\Requests\Employee\UpdateRequest $request, Employee $employee)
    {
        try {
            DB::transaction(function () use ($request, $employee) 
            {
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
                    $randomPassword = Str::random(10);

                    $userDetails = $userDetails + [
                        'password' => Hash::make($randomPassword)
                    ];

                    dispatch(
                        new QueueEmployeeEmailVerificationNotification($user, $randomPassword)
                    )->delay(5);
                }

                $user->update($userDetails);
                $user->syncRoles($request->role_id);

                $employee->update($request->validated());
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

                User::whereIn('email', $employeeEmails)->delete();
                
                Employee::whereIn('id', $request->ids)->delete();
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }

}