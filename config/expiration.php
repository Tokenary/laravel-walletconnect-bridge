<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Session expiration time bindings
    |--------------------------------------------------------------------------
    */

    'session' => env('SESSION_EXPIRATION', 86400),
    'call'    => env('CALL_EXPIRATION', 3600),

];
