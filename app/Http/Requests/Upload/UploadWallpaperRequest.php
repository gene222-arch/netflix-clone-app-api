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
                'max:2048'
            ],
        ];
    }
}
