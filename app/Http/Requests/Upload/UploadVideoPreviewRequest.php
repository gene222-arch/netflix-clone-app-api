<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;

class UploadVideoPreviewRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'video_preview' => ['required', 'file', 'mimes:mp4,ogx,oga,ogv,ogg,webm', 'max:100000'],
        ];
    }
}
