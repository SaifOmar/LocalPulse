
<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Account;
use App\Helpers\Helpers;
use App\Models\Pulse;
use App\Models\User;

describe('User update pulses tests', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->account = Account::create([
            'user_id' => $this->user->id,
            'handle' => '@saifomar',
            'password' => Hash::make('password'),
        ]);
        $token = Helpers::createUserToken($this->user, $this->account->handle);
        $this->withHeaders([
            "Authorization" => "Bearer " . $token,
        ]);
        $this->pulse = Pulse::factory()->create([
            'account_id' => $this->account->id,
        ]);
    });

    it('can update pulse ', function () {
        $response = $this->putJson("api/pulses/{$this->pulse->id}/update", [
            'caption' => 'test_testCaption',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
            'id',
            'account_id',
            'type',
            'caption',
            'url',
            ]
        ]);
    });

    it('can update pulse with tags', function () {
        $response = $this->putJson("api/pulses/{$this->pulse->id}/update", [
            'caption' => 'test_testCaption',
            'tags' => ['tag11', 'tag2'],
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
            'id',
            'account_id',
            'type',
            'caption',
            'tags' => [
            '*' => [
                'name',
                'slug',
            ],
        ],
            'url',
            ]
        ]);
    });

    it('can update pulse with the same tags without recreation', function () {
        $response = $this->putJson("api/pulses/{$this->pulse->id}/update", [
            'caption' => 'test_testCaption',
            'tags' => ['tag11', 'tag11'],
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
            'id',
            'account_id',
            'type',
            'caption',
            'tags' => [
            '*' => [
                'name',
                'slug',
            ],
        ],
            'url',
            ]
        ]);
    });

});
