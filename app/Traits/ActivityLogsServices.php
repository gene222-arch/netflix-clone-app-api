<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait ActivityLogsServices
{
    public static function createLog(string $actionType, string $modelType, ?string $urlViewPath = null, ?string $description = null): ActivityLog
    {
        $data = [
            'type' => $actionType,
            'model_type' => $modelType,
            'description' => $description,
            'view_data_path' => $urlViewPath
        ];
        
        return ActivityLog::create($data);
    }
}