<?php

namespace App\Http\Requests\Movie\MyDownload;

use App\Http\Requests\BaseRequest;

class DestroyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids.*' => [ 'required', 'integer', 'exists:my_downloads,id' ]
        ];
    }
}
