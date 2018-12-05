<?php
declare(strict_types=1);

namespace App\Services\External;

class PushService extends MasterConnectionService
{
    /**
     * PushService constructor.
     *
     * @param $webhook
     */
    public function __construct($webhook)
    {
        parent::__construct($webhook);
    }

    /**
     * @param $sessionId
     * @param $callId
     * @param $type
     * @param $token
     * @param $dappName
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendPush($sessionId, $callId, $type, $token, $dappName) : void
    {
        $this->sendJsonPostRequest('', [
            'sessionId' => $sessionId,
            'callId'    => $callId,
            'pushType'  => $type,
            'pushToken' => $token,
            'dappName'  => $dappName,
        ]);
    }
}
