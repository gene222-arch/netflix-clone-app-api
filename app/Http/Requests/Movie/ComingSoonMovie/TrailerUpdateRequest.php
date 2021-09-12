<?php

namespace App\Http\Requests\Movie\ComingSoonMovie;

use App\Http\Requests\BaseRequest;

class TrailerUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:coming_soon_movie_trailers'],
            'coming_soon_movie_id' => ['required', 'integer', 'exists:coming_soon_movies,id'],
            'title' => ['required', 'string', "unique:coming_soon_movie_trailers,title,{$this->id}"],
            'author_ids.*' => ['required', 'integer', 'exists:authors,id'],
            'poster_path' => ['required', 'string'],
            'wallpaper_path' => ['required', 'string'],
            'video_path' => ['required', 'string'],
            'title_logo_path' => ['required', 'string'],
        ];
    }
}
