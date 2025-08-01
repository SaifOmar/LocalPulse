<?php

use App\Models\Account;

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user account can be deleted', function () {
    $email = fake()->safeEmail('saif@gmail.com');
    $response = $this->post('/api/auth/users/register', [
        'first_name' => 'Saif',
        'last_name' => 'Shaikh',
        'handle' => 'SaifOmar',
        'email' => $email,
        'password' => 'password',
        "password_confirmation" => "password",
    ]);
    $response->assertStatus(201);
    $user = User::where('email', $email)->first();
    $this->assertDatabaseHas('accounts', [
        'user_id' =>  User::where('email', $email)->first()->id
    ]);
    $account = Account::where('user_id', User::where('email', $email)->first()->id)->first();
    $response = $this->delete('api/auth/accounts/' . $account->id . '/delete');
    $response->assertStatus(204);
    $this->assertSoftDeleted('accounts', [
        'user_id' =>  User::where('email', $email)->first()->id
    ]);
});
