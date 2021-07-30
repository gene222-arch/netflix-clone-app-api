<?php

namespace App\Http\Controllers\Api\Movie;

use App\Models\MyList;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\MyList\Request;
use Illuminate\Support\Facades\Auth;

class MyListsController extends Controller
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
            ->myLists()
            ->where([
                [ 'user_id', Auth::user()->id ],
                [ 'user_profile_id', $request->user_profile_id ],
                [ 'movie_id', $request->movie_id ]
            ]);
                
        if ($findInMyListQuery->exists()) 
        {
            $findInMyListQuery->delete();

            return $this->success(null, 'Removed to My List.');
        }

        Auth::user()->myLists()->create($request->validated());

        return $this->success(null, 'Added to My List.');
    }
}
