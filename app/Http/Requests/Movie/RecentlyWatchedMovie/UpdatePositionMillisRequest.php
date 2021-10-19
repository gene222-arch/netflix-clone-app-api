<?php

namespace App\Http\Requests\Movie\RecentlyWatchedMovie;

use App\Http\Requests\BaseRequest;

class UpdatePositionMillisRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'movie_id' => [ 'required', 'integer', 'exists:movies,id' ],
            'user_profile_id' => ['required', 'integer', 'exists:user_profiles,id'],
            'last_played_position_millis' => ['required', 'integer']
        ];
    }
}
