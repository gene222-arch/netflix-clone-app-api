<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Http\Requests\Employee\DestroyRequest;
use App\Traits\HasEmployeeServices;

class EmployeesController extends Controller
{
    use ApiResponser, HasEmployeeServices;

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
        $result = HasEmployeeServices::update($request, $employee);
        
        return is_string($result)
            ? $this->error($result)
            : $this->success(null, 'Employee updated successfully');
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
