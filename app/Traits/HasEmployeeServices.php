<?php

namespace App\Traits;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

trait HasEmployeeServices
{

    public static function store(\App\Http\Requests\Employee\StoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $employee = Employee::create($request->validated());
                $employee->assignRole($request->role_id);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }

    public static function update(\App\Http\Requests\Employee\UpdateRequest $request, \App\Models\Employee $employee)
    {
        try {
            DB::transaction(function () use ($request, $employee) {
                $employee->update($request->validated());
                $employee->syncRoles($request->role_id);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public static function destory(\App\Http\Requests\Employee\DestroyRequest  $request)
    {
        try {
            DB::transaction(function () use($request) {
                Employee::whereIn('id', $request->ids)->delete();
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }

}