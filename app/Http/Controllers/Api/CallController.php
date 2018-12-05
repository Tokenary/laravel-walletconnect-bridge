<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Call\IndexCallRequest;
use App\Http\Requests\Call\ShowCallRequest;
use App\Http\Requests\Call\StatusCallRequest;
use App\Http\Requests\Call\StoreCallRequest;
use App\Http\Requests\Call\UpdateCallRequest;
use App\Models\Call;
use App\Repositories\CallRepository;
use App\Repositories\SessionRepository;
use App\Services\External\PushService;
use Illuminate\Http\JsonResponse;

/**
 * Class CallController.
 *
 * @package App\Http\Controllers\Api
 */
class CallController extends BaseController
{
    /**
     * @var CallRepository
     */
    protected $callRepository;

    /**
     * @var SessionRepository
     */
    protected $sessionRepository;

    /**
     * @param CallRepository    $callRepository
     * @param SessionRepository $sessionRepository
     */
    public function __construct(CallRepository $callRepository, SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->callRepository = $callRepository;
    }

    /**
     * @param IndexCallRequest $request
     * @param string           $sessionId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(IndexCallRequest $request, string $sessionId) : JsonResponse
    {
        $callList = $this->callRepository->index($sessionId);

        return $this->respondWithSuccess(
            [
                'data' => $callList->mapWithKeys(function (Call $item) {
                    return [
                        $item->id => [
                            'encryptionPayload' => json_decode($item->encryption_payload)
                        ]
                    ];
                }),
            ]
        );
    }

    /**
     * @param StoreCallRequest $request
     * @param string           $sessionId
     *
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(StoreCallRequest $request, string $sessionId) : JsonResponse
    {
        $session = $this->sessionRepository->show($sessionId);

        if ($session === null) {
            return $this->respondNotFound(
                    'Incorrect input parameters'
                );
        }

        $call = $this->callRepository->create([
                'session_id'         => $sessionId,
                'encryption_payload' => json_encode($request->input('encryptionPayload', '')),
                'expires_in'         => generateCallLifeTime(),
            ]);

        if ($call === null) {
            return $this->respondWithError(
                    'Incorrect input parameters'
                );
        }

        $dappName = $request->input('dappName', '');

        if (isset($session->webhook)) {
            $push = new PushService($session->webhook);

            $push->sendPush(
                $sessionId,
                $call->getKey(),
                $session->type,
                $session->token,
                $dappName
            );
        }

        return $this->respondWithSuccess(
                [
                    'callId' => $call->getKey(),
                ]
            );
    }

    /**
     * @param UpdateCallRequest $request
     * @param string            $callId
     *
     * @return JsonResponse
     */
    public function update(UpdateCallRequest $request, string $callId) : JsonResponse
    {
        $call = $this->callRepository->show($callId);

        if ($call === null) {
            return $this->respondNotFound(
                'Incorrect input parameters'
            );
        }

        $attributes = [
            'status' => json_encode($request->input('encryptionPayload', '')),
        ];

        if ($this->callRepository->update($callId, $attributes)) {
            return $this->respondCreated();
        }

        return $this->respondWithError(
            'Error unknown', 500
        );
    }

    /**
     * @param ShowCallRequest $request
     * @param string          $sessionId
     * @param string          $callId
     *
     * @return JsonResponse
     */
    public function show(ShowCallRequest $request, string $sessionId, string $callId) : JsonResponse
    {
        $call = $this->callRepository->show($callId);

        if ($call === null) {
            return $this->respondNotFound(
                'Incorrect input parameters'
            );
        }

        return $this->respondWithSuccess(
            [
                'data' => [
                    'encryptionPayload' => json_decode($call->encryption_payload),
                ],
            ]
        );
    }

    /**
     * @param StatusCallRequest $request
     * @param string            $callId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function status(StatusCallRequest $request, string $callId) : JsonResponse
    {
        $call = $this->callRepository->show($callId);

        if ($call === null) {
            return $this->respondNotFound(
                'Incorrect input parameters'
            );
        }

        if ($call->status !== null) {
            $this->callRepository->delete($callId);

            return $this->respondWithSuccess(
                [
                    'data' => [
                        'encryptionPayload' => json_decode($call->status),
                    ],
                ]
            );
        }

        return $this->respondNoContent();
    }
}
