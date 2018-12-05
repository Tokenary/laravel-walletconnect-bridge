<?php

namespace Tests\Feature;

use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionTest extends TestCase
{
//    use RefreshDatabase;

    public function testDelete(): void
    {
        $sessionId = $this->testStore();
        $this->assertEquals($this->sessionExists($sessionId), true);

        $response = $this->json('DELETE', '/session/' . $sessionId);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'success' => true,
            ]);

        $this->assertEquals($this->sessionExists($sessionId), false);
    }

    public function testStore()
    {
        $response = $this->json('POST', '/session/new');

        $response
            ->assertStatus(200)
            ->assertJson([
                'sessionId' => true,
            ]);
        $sessionId = $response->original['sessionId'];
        $this->assertDatabaseHas('sessions', ['id' => $sessionId]);

        return $sessionId;
    }

    /**
     * @param $sessionId
     * @return bool
     */
    public function sessionExists($sessionId): bool
    {
        $exist = Session::where('id', $sessionId)->exists();
        return $exist;
    }

    public function testShowWithNoContent(): void
    {
        $sessionId = $this->testStore();

        $response = $this->json('get', '/session/' . $sessionId);
        $response->assertStatus(204);
    }

    public function testShow()
    {
        $sessionId = $this->testUpdate();
        $session = Session::where('id', $sessionId)->first();

        $response = $this->json('get', '/session/' . $sessionId);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'encryptionPayload' => [
                        'data' => json_decode($session->encryption_payload)->data,
                        'hmac' => json_decode($session->encryption_payload)->hmac,
                        'iv'   => json_decode($session->encryption_payload)->iv,
                    ],
                    'expires'           => true,
                ],
            ]);


        $cipher = createAes(); // could use AES::MODE_CBC

        $this->assertEquals(config('test-data.session_data_approved'),
            $cipher->decrypt(hex2bin($response->original['data']['encryptionPayload']->data)));
    }

    public function testUpdate()
    {
        $sessionId = $this->testStore();

        $cipher = createAes(); // could use AES::MODE_CBC

        $data = [
            'push'              => [
                'token'   => '03df25c845d460bcdad7802d2vf6fc1dfde97283bf75cc993eb6dca835ea2e2f',
                'type'    => 'apn',
                'webhook' => 'https://api-dev.tokenary.io/api/v1/wc/push',
            ],
            'encryptionPayload' => [
                'data' => bin2hex($cipher->encrypt(config('test-data.session_data_approved'))),
                'hmac' => $cipher->key,
                'iv'   => bin2hex($cipher->iv),
            ],
        ];
        $response = $this->json('PUT', '/session/' . $sessionId, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'expires' => true,
            ]);

        return $sessionId;
    }
}
