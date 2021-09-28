<?php

namespace Tests\Feature\Http\Controllers\Api\Dashboards;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardsControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_dashboard_data()
    {
        $response = $this->get(
            '/api/dashboard',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }
}
