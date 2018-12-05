<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Session\DestroySessionRequest;
use App\Http\Requests\Session\ShowSessionRequest;
use App\Http\Requests\Session\UpdateSessionRequest;
use App\Repositories\SessionRepository;
use Illuminate\Http\JsonResponse;

/**
 * Class SessionController.
 *
 * @package App\Http\Controllers\Api
 */
class SessionController extends BaseController
{
    /**
     * @var SessionRepository
     */
    protected $sessionRepository;

    /**
     * @param SessionRepository $sessionRepository
     */
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * @return JsonResponse
     */
    public function store() : JsonResponse
    {
        $session = $this->sessionRepository->create([
            'expires_in' => generateSessionLifeTime(),
        ]);

        return $this->respondWithSuccess(
            [
                'sessionId' => $session->getKey(),
            ]
        );
    }

    /**
     * @param ShowSessionRequest $request
     * @param string             $sessionId
     *
     * @return JsonResponse
     */
    public function show(ShowSessionRequest $request, string $sessionId): JsonResponse
    {
        $session = $this->sessionRepository->show($sessionId);

        if ($session === null || $session->encryption_payload === null) {
            return $this->respondNoContent();
        }

        return $this->respondWithSuccess(
            [
                'data' => [
                    'encryptionPayload' => json_decode($session->encryption_payload),
                    'expires'           => getDifferenceMinutes($session->expires_in),
                ],
            ]
        );
    }

    /**
     * @param UpdateSessionRequest $request
     * @param string               $sessionId
     *
     * @return JsonResponse
     */
    public function update(UpdateSessionRequest $request, string $sessionId): JsonResponse
    {
        $session = $this->sessionRepository->show($sessionId);

        if ($session === null) {
            return $this->respondNotFound(
                'Incorrect input parameters'
            );
        }

        $attributes = [
            'encryption_payload' => json_encode($request->input('encryptionPayload', '')),
            'expires_in'         => generateSessionLifeTime(),
            'token'              => $request->input('push.token', ''),
            'type'               => $request->input('push.type', ''),
            'webhook'            => $request->input('push.webhook', ''),
        ];

        if ($this->sessionRepository->update($sessionId, $attributes)) {
            return $this->respondWithSuccess(
                [
                    'expires' => getDifferenceMinutes($session->expires_in),
                ]
            );
        }

        return $this->respondWithError('Error unknown', 500);
    }

    /**
     * @param DestroySessionRequest $request
     * @param string                $sessionId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(DestroySessionRequest $request, string $sessionId): JsonResponse
    {
        $session = $this->sessionRepository->show($sessionId);

        if ($session === null) {
            return $this->respondNotFound(
                'Incorrect input parameters'
            );
        }

        if ($this->sessionRepository->delete($sessionId)) {
            return $this->respondWithSuccess(
                [
                    'success' => true,

                ]
            );
        }

        return $this->respondNoContent();
    }
}
