<?php

namespace App\Http\Requests\Movie\Movie;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:movies'],
            'title' => ['required', 'string', "unique:movies,title,{$this->id}"],
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
            'directors' => ['required', 'string'],
            'authors' => ['required', 'string'],
            'poster_path' => ['required', 'string'],
            'wallpaper_path' => ['required', 'string'],
            'video_path' => ['required', 'string'],
            'title_logo_path' => ['required', 'string'],
            'video_size_in_mb' => ['required', 'numeric']
        ];
    }
}
