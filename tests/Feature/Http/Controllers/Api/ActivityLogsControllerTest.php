<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityLogsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_activity_logs()
    {
        $response = $this->get(
            '/api/activity-logs',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_activity_log()
    {
        $id = 1;

        $response = $this->get(
            "/api/activity-logs/$id",
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_activity_log()
    {
        $data = [
            'description' => 'Update Something'
        ];

        $response = $this->post(
            '/api/activity-logs',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_update_activity_log()
    {
        $id = 1;

        $data = [
            'description' => ''
        ];

        $response = $this->put(
            '/api/activity-logs/' . $id,
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
    

    /** test */
    public function user_can_delete_activity_logs()
    {
        $data = [
            'ids' => [1]
        ];

        $response = $this->delete(
            "/api/activity-logs",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

}
