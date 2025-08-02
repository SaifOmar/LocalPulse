<?php

use App\Actions\Accounts\CreateUserAccountAction;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can create account for user with valid attributes', function () {
    $action = new CreateUserAccountAction();
    $user = User::factory()->create();
    $data = [
        'handle' => 'SaifOmar',
    ];
    $account = $action->first($user, $data);
    $this->assertDatabaseHas('accounts', [
        'user_id' =>  $user->id
    ]);
    $handle = "@" . 'saifomar';
    $this->assertEquals($account->handle, $handle);
});
