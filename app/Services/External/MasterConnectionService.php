<?php

declare(strict_types=1);

namespace App\Services\External;

use File;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

class MasterConnectionService
{
    protected $client;
    protected $apiPrefix;
    protected $headers;
    protected $auth;

    protected $successMessage;
    protected $successData;
    protected $errorMessage;
    protected $errorData;

    protected $contentType;
    protected $contentDisposition;

    /**
     * MasterConnectionService constructor.
     *
     * @param string $host
     * @param string $prefix
     */
    public function __construct(string $host, $prefix = '')
    {
        $this->client = new Client([
            'base_uri' => $host,
            'headers'  => [
                'Content-Type'    => 'application/json',
                'connect_timeout' => 30,
                'timeout'         => 60,
            ],
        ]);
        $this->apiPrefix = $prefix;
        $this->headers = [];
        $this->auth = [];
        $this->clearResults();
    }

    /**
     * @return $this
     */
    protected function clearResults()
    {
        $this->successMessage = null;
        $this->successData = null;
        $this->errorMessage = null;
        $this->errorData = null;

        return $this;
    }

    /**
     * @param string $key
     * @param        $value
     *
     * @return $this
     */
    public function setHeader(string $key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function flushHeaders()
    {
        $this->headers = [];

        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return $this
     */
    public function setAuth(string $username, string $password)
    {
        $this->auth = [$username, $password];

        return $this;
    }

    // Main Functions

    /**
     * @return $this
     */
    public function flushAuth()
    {
        $this->auth = [];

        return $this;
    }

    /**
     * @param string $url
     * @param array  $queryParams
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendGetRequest($url, $queryParams = []): bool
    {
        return $this->performRequest('GET', $url, $queryParams);
    }

    /**
     * @param string $type
     * @param string $url
     * @param array  $data
     * @param string $serializer
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool
     */
    protected function performRequest($type, $url, $data = [], $serializer = 'multipart'): bool
    {
        $this->clearResults();

        try {
            $options = [
                (mb_strtolower($type) === 'get' ? 'query' : $serializer) => $data,
            ];

            if (count($this->headers) > 0) {
                $options = array_merge(['headers' => $this->headers], $options);
            }

            if (count($this->auth) === 2) {
                $options = array_merge(['auth' => $this->auth], $options);
            }

            $response = $this->client->request($type, $this->apiPrefix . $url, $options);
        } catch (ClientException $e) { // 4xx
            if ($e->getCode() === 409) {
                return $this->duplicateClientResponse($e->getResponse());
            }

            return $this->processClientErrorResponse($e->getResponse());
        } catch (ServerException $e) { // 5xx
            return $this->processClientErrorResponse($e->getResponse());
        } catch (RequestException $e) { // other
            return $this->unprocessedResponse();
        }

        return $this->processSuccessResponse($response);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    protected function duplicateClientResponse(ResponseInterface $response): bool
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $reason = 'duplicate';

        return $this->failedResponse($reason, $data);
    }

    /**
     * @param string     $errorMessage
     * @param array|null $errorData
     *
     * @return bool
     */
    protected function failedResponse($errorMessage, $errorData = null): bool
    {
        $this->errorMessage = $errorMessage;
        $this->errorData = $errorData;

        return false;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    protected function processClientErrorResponse(ResponseInterface $response): bool
    {
        $data = json_decode($response->getBody()->getContents(), true);

        if (is_array($data)) {
            $reason = (array_key_exists('Error', $data)) ? $data['Error'] : 'Just failed';
        } else {
            $reason = 'Just failed';
        }

        return $this->failedResponse($reason, $data);
    }

    /**
     * @return bool
     */
    protected function unprocessedResponse()
    {
        return $this->failedResponse('Unprocessed response recieved.', ['code' => 'unprocessed_response']);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    protected function processSuccessResponse(ResponseInterface $response): bool
    {
        $this->setContentType($response->getHeader('Content-Type')[0]);

        switch ($response->getHeader('Content-Type')[0]) {
            case 'image/jpeg':
                $data = $response->getBody()->getContents();

                return $this->successfulResponse('OK', $data);

                break;
            case 'image/png':
                $data = $response->getBody()->getContents();

                return $this->successfulResponse('OK', $data);

                break;
            case 'application/pdf':
                $data = $response->getBody();
                $this->setContentDisposition($response->getHeader('Content-Disposition'));

                return $this->successfulResponse('OK', $data);

                break;
            case 'application/msword':
                $data = $response->getBody();
                $this->setContentDisposition($response->getHeader('Content-Disposition')[0]);

                return $this->successfulResponse('OK', $data);

                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $data = $response->getBody();
                $this->setContentDisposition($response->getHeader('Content-Disposition')[0]);

                return $this->successfulResponse('OK', $data);

                break;
            default:
                $data = json_decode($response->getBody()->getContents(), true);

                return $this->successfulResponse($data, $data ?? null);

                break;
        }
    }

    /**
     * @param string     $successMessage
     * @param array|null $successData
     *
     * @return bool
     */
    protected function successfulResponse($successMessage, $successData = null): bool
    {
        $this->successMessage = $successMessage;
        $this->successData = $successData;

        return true;
    }

    // Helper Functions

    /**
     * @param string $url
     * @param array  $formParams
     * @param array  $files
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendPostRequest($url, $formParams = [], $files = []): bool
    {
        $multipartData = [];
        foreach ($formParams as $name => $contents) {
            $multipartData[] = [
                'name'     => $name,
                'contents' => $contents,
            ];
        }
        foreach ($files as $name => $options) {
            $contents = null;
            if (is_array($options)) {
                if (isset($options['contents'])) {
                    $contents = $options['contents'];
                } elseif (isset($options['path'])) {
                    if (File::exists($options['path'])) {
                        $contents = fopen($options['path'], 'r');
                    }
                }
            } else {
                $contents = $options;
            }
            if ($contents) {
                $data = [
                    'name'     => $name,
                    'contents' => $contents,
                ];
                if (isset($options['filename'])) {
                    $data['filename'] = $options['filename'];
                }
                if (isset($options['mime'])) {
                    $data['headers'] = [
                        'Content-Type' => $options['mime'],
                    ];
                }
                $multipartData[] = $data;
            }
        }

        return $this->performRequest('POST', $url, $multipartData);
    }

    /**
     * @param string $url
     * @param array  $formParams
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendJsonPatchRequest($url, $formParams = []): bool
    {
        return $this->performRequest('PATCH', $url, $formParams, 'json');
    }

    /**
     * @param string $url
     * @param array  $formParams
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendJsonPostRequest($url, $formParams = []): bool
    {
        return $this->performRequest('POST', $url, $formParams, 'json');
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return array|null
     */
    public function getErrorData()
    {
        return $this->errorData;
    }

    /**
     * @return string|null
     */
    public function getSuccessMessage()
    {
        return $this->successMessage;
    }

    /**
     * @return array|null
     */
    public function getSuccessData()
    {
        return $this->successData;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param $type
     * @return bool
     */
    protected function setContentType($type): bool
    {
        $this->contentType = $type;

        return true;
    }

    /**
     * @return mixed
     */
    public function getContentDisposition()
    {
        return $this->contentDisposition;
    }

    /**
     * @param $type
     * @return bool
     */
    protected function setContentDisposition($type): bool
    {
        $this->contentDisposition = $type;

        return true;
    }

    /**
     * @param string $url
     * @param array  $formParams
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendDeleteRequest($url, $formParams = []): bool
    {
        return $this->performRequest('DELETE', $url, $formParams);
    }
}
