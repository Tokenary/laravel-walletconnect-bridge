<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Call;

/**
 * Class CallRepository.
 *
 * @package App\Repositories
 */
class CallRepository extends BaseRepository
{
    /**
     * @param Call $model
     */
    public function __construct(Call $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $sessionId
     *
     * @return Call[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(string $sessionId)
    {
        return $this->model
            ->where('session_id', $sessionId)
            ->where('expires_in', '>=', getNow())
            ->get();
    }

    /**
     * @param string $callId
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(string $callId): bool
    {
        return (bool)$this->model
            ->where('id', $callId)
            ->delete();
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes): Call
    {
        return $this->model
            ->create($attributes);
    }

    /**
     * @param string $callId
     * @param array  $attributes
     *
     * @return bool
     */
    public function update(string $callId, array $attributes): bool
    {
        return (bool)$this->model
            ->where('id', $callId)
            ->update($attributes);
    }

    /**
     * @param string $callId
     *
     * @return Call|null
     */
    public function show(string $callId): ?Call
    {
        return $this->model
            ->where('id', $callId)
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
