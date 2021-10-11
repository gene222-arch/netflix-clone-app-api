<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\DestroyRequest;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;

class EmployeesController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = Employee::withCount('roles')->get();

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
        Employee::create($request->validated());

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
        return $this->success($employee);
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
        $employee->update($request->validated());
        
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
