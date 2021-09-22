<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;
use App\Rules\MimeType;

class UploadAvatarRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


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
                new MimeType(['jpg', 'jpeg'])
            ],
        ];
    }
}
