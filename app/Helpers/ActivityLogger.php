<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log($aksi, $model = null, $before = null, $after = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'model' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'before' => $before ? json_encode($before) : null,
            'after' => $after ? json_encode($after) : null,
            'ip' => request()->ip(),
        ]);
    }
}
