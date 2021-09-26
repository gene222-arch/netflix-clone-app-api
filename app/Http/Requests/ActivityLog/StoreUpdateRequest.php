<?php

namespace App\Http\Requests\ActivityLog;

use App\Http\Requests\BaseRequest;

class StoreUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', 'string', 'in:Update,Delete,Create'],
            'model_type' => ['required', 'string', 'in:Author,Cast,Create,Director,Genre,Movie,ComingSoonMovie,Trailer'],
            'url_view_path' => ['nullable', 'string', 'url'],
            'description' => ['nullable', 'string', 'min:12']
        ];
    }
}
