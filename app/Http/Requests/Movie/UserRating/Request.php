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
        $movieExists = $this->model_type === 'Movie' ? 'exists:movies,id' : 'exists:coming_soon_movies,id';

        return [
            'movie_id' => ['required', 'integer', $movieExists],
            'user_profile_id' => ['required', 'integer', 'exists:user_profiles,id'],
            'rate' => ['nullable', 'string', 'in:like,dislike'],
            'model_type' => ['required', 'string', 'in:Movie,ComingSoonMovie']
        ];
    }
}
