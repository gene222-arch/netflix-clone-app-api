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
        $isRequired = $this->status === 'Coming Soon' ? 'required' : 'nullable';

        return [
            'video_path' => [$isRequired, 'string'],
            'duration_in_minutes' => [$isRequired, 'integer'],
            'video_size_in_mb' => [$isRequired, 'numeric'],
            'status' => ['required', 'string', 'in:Coming Soon,Released']
        ];
    }

    public function messages()
    {
        return [
            'video_path.required' => 'Video file is required.'
        ];
    }
}
