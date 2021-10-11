<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            'first_name' => 'Brix',
            'last_name' => 'Br',
            'email' => 'br@gmail.com',
            'phone' => '192647745578',
            'pin_code' => '3333',
            'role_id' => 5
        ];

        $response = $this->post(
            '/api/employees',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** @test */
    public function user_can_update_employee()
    {
        $id = 2;

        $data = [
            'id' => $id,
            'first_name' => 'Namimi',
            'last_name' => 'Kurusawa',
            'email' => 'namiswan@gmail.com',
            'phone' => '092647745578',
            'pin_code' => '2222',
            'role_id' => 5
        ];

        $response = $this->put(
            "/api/employees/$id",
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
                1
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
