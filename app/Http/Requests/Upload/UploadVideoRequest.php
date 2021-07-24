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
            'title' => ['required', 'string', 'unique:movies,title,' . $this->id ?? 0],
            'video' => ['nullable', 'file', 'mimes:mp4,ogx,oga,ogv,ogg,webm', 'max:1000000'],
        ];
    }
}
