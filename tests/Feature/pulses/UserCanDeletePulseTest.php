<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Helpers\Helpers;
use App\Models\Account;
use App\Models\Mood;
use App\Models\User;
use App\Models\Pulse;

describe('User delete pulses tests', function () {
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
        Mood::factory()->create();
        $this->pulse = Pulse::factory()->create([
            'account_id' => $this->account->id,
        ]);
    });

    it('can delete pulse ', function () {
        $response = $this->deleteJson("api/pulses/{$this->pulse->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('pulses', [
            'id' => $this->pulse->id,
        ]);
    });

});
