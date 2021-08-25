<?php

namespace App\Http\Requests\Movie\MyList;

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
            'user_profile_id' => ['required', 'integer', 'exists:user_profiles,id'],
            'movie_id' => ['required', 'integer', 'exists:movies,id']
        ];
    }
}
