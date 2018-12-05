<?php

namespace Tests\Feature;

use App\Repositories\CallRepository;
use App\Repositories\SessionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CallTest extends TestCase
{
//    use RefreshDatabase;

    public function testShow()
    {
        $sessionRepository = app()->make(SessionRepository::class);
        $callRepository = app()->make(CallRepository::class);

        $sessionArray = factory(\App\Models\Session::class)->make()->getAttributes();
        $session = $sessionRepository->create($sessionArray);

        $this->assertDatabaseHas('sessions', ['id' => $session->getKey()]);

        $callId = $this->testStore();

        $call = $callRepository->show($callId);
        $response = $this->json('GET', '/session/' . $session->getKey() . '/call/' . $callId);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'encryptionPayload' => [
                        'data' => json_decode($call->encryption_payload)->data,
                        'hmac' => json_decode($call->encryption_payload)->hmac,
                        'iv'   => json_decode($call->encryption_payload)->iv,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('calls', ['id' => $callId]);

        return $callId;
    }

    public function testStore()
    {
        $sessionRepository = app()->make(SessionRepository::class);
        $sessionArray = factory(\App\Models\Session::class)->make()->getAttributes();
        $session = $sessionRepository->create($sessionArray);

        $this->assertDatabaseHas('sessions', ['id' => $session->getKey()]);

        $cipher = createAes(); // could use AES::MODE_CBC

        $data = [
            'encryptionPayload' => [
                'data' => bin2hex($cipher->encrypt(config('test-data.call_data_new'))),
                'hmac' => $cipher->key,
                'iv'   => bin2hex($cipher->iv),
            ],
            'dappName'          => config('test-data.dapp_name'),
        ];

        $response = $this->json('POST', '/session/' . $session->getKey() . '/call/new', $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'callId' => true,
            ]);

        $callId = $response->original['callId'];
        $this->assertDatabaseHas('calls', ['id' => $callId]);

        return $callId;
    }

    public function testCallStatusStore()
    {
        $callId = $this->testStore();

        $data = ['encryptionPayload' => 'testing'];
        $response = $this->json('POST', '/call-status/' . $callId . '/new', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('calls', ['id' => $callId]);

        return $callId;
    }

    public function testCallStatusShow()
    {
        $callId = $this->testCallStatusUpdate();

        $cipher = createAes(); // could use AES::MODE_CBC

        $response = $this->json('GET', '/call-status/' . $callId);
        $response->assertStatus(200);

        $this->assertEquals(config('test-data.call_status_success'),
            $cipher->decrypt(hex2bin($response->original['data']['encryptionPayload']->data)));
    }

    public function testCallStatusUpdate()
    {
        $callId = $this->testStore();

        $cipher = createAes(); // could use AES::MODE_CBC

        $data = [
            'encryptionPayload' => [
                'data' => bin2hex($cipher->encrypt(config('test-data.call_status_success'))),
                'hmac' => $cipher->key,
                'iv'   => bin2hex($cipher->iv),
            ],
        ];
        $response = $this->json('PUT', '/call-status/' . $callId, $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('calls', ['id' => $callId]);

        return $callId;
    }
}
