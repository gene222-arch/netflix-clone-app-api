<?php

namespace App\Http\Requests\Movie\ComingSoonMovie;

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
            'title' => ['required', 'string', 'unique:coming_soon_movies'],
            'plot' => ['required', 'string'],
            'duration_in_minutes' => ['required', 'numeric'],
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
            'similar_movie_ids.*' => ['nullable', 'distinct', 'integer', 'exists:movies,id'],
            'poster_path' => ['required', 'string'],
            'wallpaper_path' => ['required', 'string'],
            'video_trailer_path' => ['required', 'string'],
            'title_logo_path' => ['required', 'string'],
            'video_size_in_mb' => ['required', 'numeric'],
            'status' => ['required', 'string', 'in:Released,Coming Soon']
        ];
    }

    public function messages()
    {
        return [
            'similar_movie_ids.*.nullable' => 'Similar movies is nullable',
            'similar_movie_ids.*.exists' => 'A non existing movie is selected'
        ];
    }
}
