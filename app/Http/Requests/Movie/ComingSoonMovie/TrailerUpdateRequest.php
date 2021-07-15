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
            'poster' => [
                'nullable', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=300,min_height=300,max_width=2000,max_height=3000', 
                'max:2048'
            ],
            'wallpaper' => [
                'nullable', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=1000,min_height=500,max_width=3000,max_height=2500',
                'max:2048'
            ],
            'video' => ['nullable', 'file', 'mimes:mp4,ogx,oga,ogv,ogg,webm', 'max:50000'],
            'title_logo' => [
                'nullable', 
                'image', 
                'mimes:png', 
                'dimensions:min_width=1280,min_height=288,max_width=1280,max_height=288', 
                'max:2048'
            ],
        ];
    }
}
