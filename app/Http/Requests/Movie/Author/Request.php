<?php

namespace App\Http\Requests\Movie\Author;

use App\Http\Requests\BaseRequest;

class Request extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avatar_path' => ['required', 'string'],
            'pseudonym' => ['nullable', 'string'],
            'birth_name' => ['required', 'string'],
            'gender' => ['required', 'string', 'in:Male,Female'],
            'height_in_cm' => ['required', 'numeric'],
            'biographical_information' => ['nullable', 'string'],
            'birth_details' => ['nullable', 'string'],
            'date_of_birth' => ['required', 'date'],
            'place_of_birth' => ['nullable', 'string'],
            'death_details' => ['nullable', 'string'],
            'date_of_death' => ['nullable', 'date'],
            'enabled' => ['required', 'boolean'],
        ];
    }
}
