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
            'wallpaper' => [
                'required', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=1000,min_height=500,max_width=3000,max_height=2500',
                'max:2048'
            ],
        ];
    }
}
