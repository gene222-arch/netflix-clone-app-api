<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;

class UploadWallpaperRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'unique:movies,title,' . $this->id ?? 0],
            'wallpaper' => [
                'nullable', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=1000,min_height=500,max_width=3000,max_height=2500',
                'max:2048'
            ],
        ];
    }
}
