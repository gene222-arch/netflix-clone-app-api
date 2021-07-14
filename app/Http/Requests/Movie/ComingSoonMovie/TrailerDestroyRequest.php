<?php

namespace App\Http\Requests\Movie\ComingSoonMovie;

use App\Http\Requests\BaseRequest;

class TrailerDestroyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids.*' => ['required', 'integer', 'exists:coming_soon_movie_trailers,id'] 
        ];
    }
}
