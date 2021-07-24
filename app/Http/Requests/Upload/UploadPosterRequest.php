<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;

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
            'title' => ['required', 'string', 'unique:movies,title,' . $this->id ?? 0],
            'poster' => [
                'nullable', 
                'image', 
                'mimes:jpeg,jpg', 
                'dimensions:min_width=300,min_height=300,max_width=2000,max_height=3000', 
                'max:2048'
            ],
        ];
    }
}
