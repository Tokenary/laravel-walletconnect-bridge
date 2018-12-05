<?php

declare(strict_types=1);

use phpseclib\Crypt\AES;

if (!function_exists('getDifferenceMinutes')) {

    /**
     * Calculate the difference in minutes from now() to timestamp.
     *
     * @param int $timestamp
     *
     * @return int
     */
    function getDifferenceMinutes(int $timestamp) : int
    {
        return (int) \Carbon\Carbon::createFromTimestamp($timestamp)->timestamp * 1000;
    }
}


if (!function_exists('generateUuid')) {

    /**
     * @return string
     */
    function generateUuid() : string
    {
        return \Illuminate\Support\Str::uuid()->toString();
    }
}


if (!function_exists('generateSessionLifeTime')) {

    /**
     * @return \Carbon\Carbon
     */
    function generateSessionLifeTime()
    {
        return getNow()->addSeconds(
            (int) config('expiration.session')
        );
    }
}

if (!function_exists('generateCallLifeTime')) {

    /**
     * @return \Carbon\Carbon
     */
    function generateCallLifeTime() : \Carbon\Carbon
    {
        return getNow()->addSeconds(
            (int) config('expiration.call')
        );
    }
}

if (!function_exists('getNow')) {

    /**
     * @return \Carbon\Carbon
     */
    function getNow() : \Carbon\Carbon
    {
        return \Carbon\Carbon::now(0);
    }
}

if (!function_exists('createAes')) {

    /**
     * @return AES
     */
    function createAes() : AES
    {
        $cipher = new AES(); // could use AES::MODE_CBC
        $cipher->setKeyLength(128);
        $cipher->setKey(config('test-data.symKey'));
        $cipher->setIV(config('test-data.IV'));

        return $cipher;
    }
}
