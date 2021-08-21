<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;
use App\Rules\MimeType;

class UploadPosterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'poster' => [
                'required', 
                'image', 
                new MimeType(['jpg', 'jpeg']),
                'dimensions:min_width=300,min_height=300,max_width=2000,max_height=3000', 
                'max:2048'
            ],
        ];
    }
}
