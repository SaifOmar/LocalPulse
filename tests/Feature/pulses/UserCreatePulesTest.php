<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Account;
use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Http\UploadedFile;

describe('User create pulses tests', function () {
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
    });

    it('can create pulse ', function () {
        $response = $this->postJson('/api/pulses', [
            'caption' => 'test',
            'type' => 'image',
            // HACK: this is just to get around doing the jwt stuff for now
            'account_id' => $this->account->id,
            //
            'media' =>  UploadedFile::fake()->image('avatar.png', 1024, 1024)->size(1024),
        ]);
        $response->assertStatus(201);
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
    it('can create pulse with tags', function () {
        $response = $this->postJson('/api/pulses', [
            'caption' => 'test',
            'tags' => ['tag1', 'tag2'],
            'type' => 'image',
            'account_id' => $this->account->id,
            'media' =>  UploadedFile::fake()->image('avatar.png', 1024, 1024)->size(1024),
        ]);
        $response->assertStatus(201);
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
