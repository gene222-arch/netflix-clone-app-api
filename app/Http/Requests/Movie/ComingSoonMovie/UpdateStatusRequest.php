<?php

namespace App\Http\Requests\Movie\ComingSoonMovie;

use App\Http\Requests\BaseRequest;

class UpdateStatusRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'video_path' => ['nullable', 'string'],
            'duration_in_minutes' => ['nullable', 'integer'],
            'video_size_in_mb' => ['nullable', 'numeric'],
        ];
    }
}
