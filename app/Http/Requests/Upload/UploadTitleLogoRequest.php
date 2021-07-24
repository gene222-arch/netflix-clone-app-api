<?php

namespace App\Http\Requests\Upload;

use App\Http\Requests\BaseRequest;

class UploadTitleLogoRequest extends BaseRequest
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
            'title_logo' => [
                'nullable', 
                'image', 
                'mimes:png', 
                'dimensions:min_width=1280,min_height=288,max_width=1280,max_height=580', 
                'max:2048'
            ],
        ];
    }
}
