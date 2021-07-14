<?php

namespace App\Http\Requests\Movie\Genre;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:genres'],
            'name' => ['required', 'string', "unique:genres,name,{$this->id}"],
            'description' => ['nullable', 'string'],
            'enabled' => ['required', 'boolean']
        ];
    }
}
