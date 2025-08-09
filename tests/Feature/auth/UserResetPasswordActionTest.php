<?php

use App\Models\Account;
use App\Helpers\Helpers;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('User reset password tests in aciton', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->account = Account::create([
            'user_id' => $this->user->id,
            'handle' => '@saifomar2',
            'password' => Hash::make('password'),
        ]);
        $this->token = Helpers::createUserToken($this->user, $this->account->handle);
        $this->uri = 'api/auth/accounts/'. $this->account->id .'/password/update';
    });

    it("can reset password", function () {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response = $this->postJson('api/auth/accounts/'. $this->account->id .'/password/update');
        $response->assertJsonStructure([
            'link',
        ]);
        $response->assertStatus(200);
    });
    it("can't reset password", function () {
        $response = $this->postJson('api/auth/accounts/'. $this->account->id .'/password/update');
        $response->assertStatus(401);
    });
});
