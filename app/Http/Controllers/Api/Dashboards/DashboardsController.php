<?php

namespace App\Http\Controllers\Api\Dashboards;

use App\Http\Controllers\Controller;
use App\Traits\Api\ApiResponser;
use App\Traits\Dashboards\DashboardServices;
use Illuminate\Http\Request;

class DashboardsController extends Controller
{
    use ApiResponser, DashboardServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:View Dashboard']);
    }
    
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        setSqlModeEmpty();

        return $this->success($this->dashboard(), 'Dashboard data fetched successfully.');
    }
}
