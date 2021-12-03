<?php

namespace App\Http\Requests\Movie\ComingSoonMovie;

use App\Http\Requests\BaseRequest;

class ReleaseRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'video_path' => ['required', 'string'],
            'duration_in_minutes' => ['required', 'numeric'],
            'video_size_in_mb' => ['required', 'numeric'],
            'status' => ['required', 'string', 'in:Coming Soon']
        ];
    }

    public function messages()
    {
        return [
            'video_path.required' => 'Video file is required.'
        ];
    }
}
