<?php

namespace App\Http\Requests\Movie\Genre;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:genres'],
            'description' => ['nullable', 'string'],
            'enabled' => ['required', 'boolean']
        ];
    }
}
