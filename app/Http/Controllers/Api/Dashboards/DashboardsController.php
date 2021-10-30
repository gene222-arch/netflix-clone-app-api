<?php

namespace App\Http\Controllers\Api\Dashboards;

use App\Http\Controllers\Controller;
use App\Traits\Dashboards\DashboardServices;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardsController extends Controller
{
    use DashboardServices;

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

        $filterYear = (int) $request->input('year', Carbon::parse(now())->format('Y'));

        return $this->success($this->dashboard($filterYear), 'Dashboard data fetched successfully.');
    }
}
