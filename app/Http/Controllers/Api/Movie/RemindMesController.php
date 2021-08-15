<?php

namespace App\Http\Controllers\Api\Movie;

use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Movie\RemindMe\Request;

class RemindMesController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }
    
    /**
     * Store a newly created resource in storage or delete if it exists.
     *
     * @param  App\Http\Requests\Movie\MyList\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request)
    {
        $authUser = $request->user('api');

        $remindMes = $authUser->remindMes();

        $findInMyListQuery = $remindMes->where([
                [ 'user_id', $authUser->id ],
                [ 'user_profile_id', $request->user_profile_id ],
                [ 'coming_soon_movie_id', $request->coming_soon_movie_id ]
            ]);
                
        if (! $findInMyListQuery->exists()) 
        {
            $remindMes->create($request->validated());

            return $this->success(null, 'Reminded.');
        }
        
        $findInMyListQuery->delete();

        return $this->success(null, 'Removed to Reminded Movies.');
    }
}
