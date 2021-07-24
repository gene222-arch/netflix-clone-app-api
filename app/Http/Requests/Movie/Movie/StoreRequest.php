<?php

namespace App\Http\Requests\Movie\Movie;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'unique:movies'],
            'plot' => ['required', 'string'],
            'year_of_release' => ['required', 'integer'],
            'date_of_release' => ['required', 'date'],
            'duration_in_minutes' => ['required', 'integer'],
            'age_restriction' => ['required', 'integer'],
            'country' => ['required', 'string'],
            'language' => ['required', 'string'],
            'casts' => ['required', 'string'],
            'cast_ids.*' => ['required', 'integer', 'exists:casts,id'],
            'genres' => ['required', 'string'],
            'genre_ids.*' => ['required', 'integer', 'exists:genres,id'],
            'directors' => ['required', 'string'],
            'director_ids.*' => ['required', 'integer', 'exists:directors,id'],
            'authors' => ['required', 'string'],
            'author_ids.*' => ['required', 'integer', 'exists:authors,id'],
            'poster_path' => ['required', 'string'],
            'wallpaper_path' => ['required', 'string'],
            'video_path' => ['required', 'string'],
            'title_logo_path' => ['required', 'string'],
            'video_size_in_mb' => ['required', 'numeric']
        ];
    }
}
