<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Session;
use Exception;

/**
 * Class SessionRepository
 *
 * @package App\Repositories
 */
class SessionRepository extends BaseRepository
{
    /**
     * NumberTemplateRepository constructor.
     *
     * @param Session $model
     */
    public function __construct(Session $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $sessionId
     *
     * @throws Exception
     *
     * @return bool
     */
    public function delete(string $sessionId) : bool
    {
        return (bool) $this->model
            ->where('id', $sessionId)
            ->delete();
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes) : ?Session
    {
        return $this->model
            ->create($attributes);
    }

    /**
     * @param string $sessionId
     * @param array  $attributes
     *
     * @return bool
     */
    public function update(string $sessionId, array $attributes) : bool
    {
        return (bool) $this->model
            ->where('id', $sessionId)
            ->update($attributes);
    }

    /**
     * @param $sessionId
     *
     * @return Session|null
     */
    public function show($sessionId) : ?Session
    {
        return $this->model
            ->where('id', $sessionId)
            ->where('expires_in', '>=', getNow())
            ->first();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function deleteAllExpired() : void
    {
        $this->model
            ->where('expires_in', '<', getNow())
            ->delete();
    }
}
