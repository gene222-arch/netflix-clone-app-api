<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait ActivityLogsServices
{
    public static function createLog(string $urlViewPath, ?string $description = null): ActivityLog
    {
        $data = [
            'type' => 'Create',
            'description' => $description,
            'url_view_path' => $urlViewPath
        ];
        
        return ActivityLog::create($data);
    }
}