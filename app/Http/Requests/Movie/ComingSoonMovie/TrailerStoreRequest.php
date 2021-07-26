<?php

namespace App\Http\Requests\Movie\ComingSoonMovie;

use App\Http\Requests\BaseRequest;

class TrailerStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'coming_soon_movie_id' => ['required', 'integer', 'exists:coming_soon_movies,id'],
            'title' => ['required', 'string', 'unique:coming_soon_movie_trailers'],
            'poster_path' => [ 'required', 'string'],
            'wallpaper_path' => [ 'required', 'string'],
            'video_path' => [ 'required', 'string'],
            'title_logo_path' => [ 'required', 'string'],
        ];
    }
}
