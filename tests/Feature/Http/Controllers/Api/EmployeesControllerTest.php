<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmployeesControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_employees()
    {
        $response = $this->get(
            '/api/employees',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_employee()
    {
        $id = 1;

        $response = $this->get(
            "/api/employees/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_employee()
    {
        $data = [
            'first_name' => 'Employee',
            'last_name' => 'Number Two',
            'email' => 'alcaraznicobryan14@gmail.com',
            'phone' => '231231312332',
            'pin_code' => '4221',
            'avatar_path' => 'avatar path',
            'role_id' => 5
        ];

        $response = $this->post(
            '/api/employees',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_employee()
    {
        $id = 9;

        $data = [
            'id' => $id,
            'first_name' => 'Namimis',
            'last_name' => 'Kurusaswa',
            'email' => 'alcaraznicobryan14@gmail.com',
            'phone' => '123123123',
            'pin_code' => '3151',
            'role_id' => 6,
            'avatar_path' => 'some path'
        ];

        $response = $this->put(
            "/api/employees/$id",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function employee_can_verify_account()
    {
        $id = 109;
        $hash = 'asdasasdad';

        $data = [];

        $response = $this->put(
            "/api/employees/verify/email?id=$id&hash=$hash",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_upload_avatar()
    {
        $data = [
            'avatar' => UploadedFile::fake()->image('employee_avatar.jpg', 300, 300)
        ];

        $response = $this->post(
            "/api/employees/avatar",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_delete_employees()
    {
        $data = [
            'ids' => [
                11
            ]
        ];

        $response = $this->delete(
            "/api/employees",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
