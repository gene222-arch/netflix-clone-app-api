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
                'max:2048'
            ],
        ];
    }
}
