<?php

namespace App\Exceptions;

use App;
use App\Support\Api\ApiResponseTrait;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [

    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     *
     * @throws Exception
     */
    public function report(Exception $exception) : void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->respondWithError($exception->validator->getMessageBag()->first());
        }

        if ($exception instanceof QueryException) {
            return $this->respondWithError('Error writing to db', 500);
        }

        if ($exception instanceof GuzzleException) {
            return $this->respondWithError('Error sending message to walletconnect push webhook', 500);
        }

        return $this->respondWithError(
            (App::environment() === 'production') ? 'Unknown error' : $exception->getMessage()
        );
    }
}
