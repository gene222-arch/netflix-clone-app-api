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
            'poster' => [
                'required', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=300,min_height=300,max_width=2000,max_height=3000', 
                'max:2048'
            ],
            'wallpaper' => [
                'required', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=1000,min_height=500,max_width=3000,max_height=2500',
                'max:2048'
            ],
            'video' => ['required', 'file', 'mimes:mp4,ogx,oga,ogv,ogg,webm', 'max:1000000'],
            'title_logo' => [
                'required', 
                'image', 
                'mimes:png', 
                'dimensions:min_width=1280,min_height=288,max_width=1280,max_height=288', 
                'max:2048'
            ],
            'video_size_in_mb' => ['required', 'integer']
        ];
    }
}
