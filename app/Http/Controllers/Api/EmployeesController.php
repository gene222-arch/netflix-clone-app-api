<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Http\Requests\Employee\DestroyRequest;
use App\Http\Requests\Employee\LoginByPinRequest;
use App\Traits\Api\ApiServices;

class EmployeesController extends Controller
{
    use ApiResponser, ApiServices;

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\Employee\LoginByPinRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginByPin(LoginByPinRequest $request)
    {
        $employee = Employee::where('pin_code', $request->pin_code)->first();

        if (! $employee) {
            return $this->error('PIN do not match. Please try again');
        }

        return $this->token(
            $employee->createToken(env('PERSONAL_ACCESS_TOKEN')),
            'Logged in successfully',
            [
                'employee' => $employee,
                'role' => $employee->roles->first()->name,
                'permissions' => $employee->getPermissionsViaRoles()->map->name,
            ]  
        );
    }


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
        try {
            DB::transaction(function () use ($request) {
                $employee = Employee::create($request->validated());
                $employee->assignRole($request->role_id);
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success(null, 'Employee created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Employee $employee)
    {
        return $this->success(Employee::with('roles')->find($employee->id));
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
        try {
            DB::transaction(function () use ($request, $employee) {
                $employee->update($request->validated());
                $employee->syncRoles($request->role_id);
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
        
        return $this->success(null, 'Employee updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Employee\DestroyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        Employee::whereIn('id', $request->ids)->delete();

        return $this->success(null, 'Employee deleted successfully.');
    }
}
