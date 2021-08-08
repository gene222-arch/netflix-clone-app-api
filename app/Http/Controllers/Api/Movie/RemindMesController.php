<?php

namespace App\Http\Controllers\Api\Movie;

use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Movie\RemindMe\Request;

class RemindMesController extends Controller
{
    use ApiResponser;

    /**
     * Store a newly created resource in storage or delete if it exists.
     *
     * @param  App\Http\Requests\Movie\MyList\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request)
    {
        $findInMyListQuery = Auth::user()
            ->remindMes()
            ->where([
                [ 'user_id', Auth::user()->id ],
                [ 'user_profile_id', $request->user_profile_id ],
                [ 'coming_soon_movie_id', $request->movie_id ]
            ]);
                
        if ($findInMyListQuery->exists()) 
        {
            $findInMyListQuery->delete();

            return $this->success(null, 'Removed to Reminded Movies.');
        }

        Auth::user()->remindMes()->create($request->validated());

        return $this->success(null, 'Reminded.');
    }
}
