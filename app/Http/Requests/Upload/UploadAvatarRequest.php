<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;
use App\Rules\MimeType;

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
                new MimeType(['jpg', 'jpeg']), 
                'dimensions:min_width=320,min_height=320', 
                'max:3'
            ],
        ];
    }
}
