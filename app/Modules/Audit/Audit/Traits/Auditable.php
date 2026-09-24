<?php

declare(strict_types=1);

namespace App\Modules\Audit\Traits;

use App\Modules\Audit\Models\Activity;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            Activity::log('created', get_class($model), $model->id, null, "Created {$model->getTable()} record");
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            $changeArray = [];
            foreach ($changes as $field => $newValue) {
                $changeArray[$field] = [
                    'old' => $model->getOriginal($field),
                    'new' => $newValue,
                ];
            }
            Activity::log('updated', get_class($model), $model->id, $changeArray, "Updated {$model->getTable()} record");
        });

        static::deleted(function ($model) {
            Activity::log('deleted', get_class($model), $model->id, null, "Deleted {$model->getTable()} record");
        });
    }
}
