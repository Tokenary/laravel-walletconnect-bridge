<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Session::class, function (Faker $faker) {
    $cipher = createAes();

    return [
        'encryption_payload' => json_encode([
            'data' => bin2hex($cipher->encrypt(config('test-data.session_data_approved'))),
            'hmac' => $cipher->key,
            'iv'   => bin2hex($cipher->iv),
        ]),
        'expires_in' => generateSessionLifeTime(),
    ];
});
