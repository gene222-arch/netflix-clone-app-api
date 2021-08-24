<?php

namespace App\Http\Requests\Movie\RecentlyWatchedMovie;

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
            'movie_id' => [ 'required', 'integer', 'exists:movies,id' ],
        ];
    }
}
