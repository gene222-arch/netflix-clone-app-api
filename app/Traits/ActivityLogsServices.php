<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait ActivityLogsServices
{
    public static function createLog(string $urlViewPath, ?string $description = null): ActivityLog
    {
        $data = [
            'type' => 'Create',
            'model_type' => ActivityLog::class,
            'user_id' => request()->user('api')->id,
            'description' => $description,
            'url_view_path' => $urlViewPath
        ];
        
        return ActivityLog::create($data);
    }
}