<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var
     */
    protected $model;

    /**
     * @return mixed
     */
    public function getModel() : Model
    {
        return $this->model;
    }
}
