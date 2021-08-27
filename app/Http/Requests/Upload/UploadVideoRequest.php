<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;

class UploadVideoRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'video' => ['required', 'file', 'mimes:mp4,ogx,oga,ogv,ogg,webm', 'max:2000000'],
        ];
    }
}
