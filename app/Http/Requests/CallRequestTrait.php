<?php

namespace App\Http\Requests;

/**
 * Trait CallRequestTrait
 * @package App\Http\Requests
 */
trait CallRequestTrait
{
    /**
     * @var array
     */
    public $defaultRules = [
        'call' => 'required|uuid',
    ];

    /**
     * @var array
     */
    public $defaultMessages = [
        'call.*' => 'Incorrect input parameters',
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
