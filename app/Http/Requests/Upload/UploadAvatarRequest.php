<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;

class UploadAvatarRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avatar' => [
                'required', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=320,min_height=320', 
                'max:3'
            ],
        ];
    }
}
