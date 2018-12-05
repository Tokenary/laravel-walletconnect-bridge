<?php

namespace App\Http\Requests;

/**
 * Trait SessionRequestTrait
 * @package App\Http\Requests
 */
trait SessionRequestTrait
{
    /**
     * @var array
     */
    public $defaultRules = [
        'session' => 'required|uuid',
    ];

    /**
     * @var array
     */
    public $defaultMessages = [
        'session.*' => 'Incorrect input parameters',
    ];

    /**
     * @return array
     */
    public function getDefaultMessages(): array
    {
        return $this->defaultMessages;
    }

    /**
     * @return array
     */
    public function getDefaultRules(): array
    {
        return $this->defaultRules;
    }
}
