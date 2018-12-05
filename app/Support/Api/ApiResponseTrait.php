<?php

namespace App\Support\Api;

use Illuminate\Http\JsonResponse;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    /** @var int */
    private $statusCode = 200;

    /**
     * @param array|null  $data
     * @param string|null $message
     * @param array|null  $paginationData
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithSuccess(
        array $data = null,
        string $message = null,
        array $paginationData = null
    ) : JsonResponse {
        $responseBody = ($data !== null ? $data : []);
        if ($message !== null) {
            $responseBody['message'] = $message;
        }
        if ($paginationData !== null) {
            $responseBody['pagination'] = $paginationData;
        }
        if (empty($responseBody)) {
            $responseBody = new stdClass();
        }

        return $this->respond($responseBody);
    }

    /**
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond(array $data = [], array $headers = []) : JsonResponse
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @return int
     */
    protected function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    protected function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param string $message
     * @param int    $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError(string $message = null, $statusCode = 400) : JsonResponse
    {
        return $this->respondRawError($message, $statusCode);
    }

    /**
     * @param     $data
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    private function respondRawError($data, int $statusCode = 400) : JsonResponse
    {
        if (is_string($data)) {
            $responseBody = [];
            if ($data !== null) {
                $responseBody['message'] = $data;
            }

            if (empty($responseBody['message'])) {
                $responseBody['message'] = 'Unknown error.';
            }
        } elseif (is_array($data)) {
            $responseBody = ['messages' => $data];
        } else {
            $responseBody = ['message' => 'Unknown error'];
        }

        return $this->setStatusCode($statusCode)
            ->respond($responseBody);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondNotFound(string $message = null) : JsonResponse
    {
        return $this->respondRawError($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondNoContent() : JsonResponse
    {
        return $this->respondRawError('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated()
    {
        $this->setStatusCode(Response::HTTP_CREATED);
        return $this->respond([]);
    }
}
