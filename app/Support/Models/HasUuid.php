<?php

namespace App\Support\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasUuid.
 *
 * @package App\Models\Support
 */
trait HasUuid
{
    protected static function bootHasUuid() : void
    {
        static::creating(function (Model $model) : void {
            if (!isset($model->attributes[$model->getKeyName()])) {
                $model->incrementing = false;
                $model->attributes[$model->getKeyName()] = (string) generateUuid();
            }
        });
    }

    /**
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }
}
