<?php

namespace App\Http\Requests\Movie\UserRating;

use App\Http\Requests\BaseRequest;

class Request extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'movie_id' => ['required', 'integer', 'exists:movies,id'],
            'user_profile_id' => ['required', 'integer', 'exists:user_profiles,id'],
            'rate' => ['required', 'string', 'in:like,dislike']
        ];
    }
}
