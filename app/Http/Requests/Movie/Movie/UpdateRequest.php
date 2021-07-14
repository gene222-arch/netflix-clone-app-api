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
            'poster_path' => [
                'required', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=300,min_height=300,max_width=2000,max_height=3000', 
                'max:2048'
            ],
            'wallpaper_path' => ['required', 'image', 'mimes:jpeg,jpg', 'max:2048'],
            'video_path' => ['required', 'file', 'mimes:mp4,ogx,oga,ogv,ogg,webm', 'max:1000000'],
            'title_logo_path' => [
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
