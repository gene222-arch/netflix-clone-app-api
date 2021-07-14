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
            'poster' => [
                'required', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=300,min_height=300,max_width=2000,max_height=3000', 
                'max:2048'
            ],
            'wallpaper' => ['required', 'image', 'mimes:jpeg,jpg', 'max:2048'],
            'video' => ['required', 'file', 'mimes:mp4,ogx,oga,ogv,ogg,webm', 'max:50000'],
            'title_logo' => [
                'required', 
                'image', 
                'mimes:png', 
                'dimensions:min_width=1280,min_height=288,max_width=1280,max_height=288', 
                'max:2048'
            ],
        ];
    }
}
