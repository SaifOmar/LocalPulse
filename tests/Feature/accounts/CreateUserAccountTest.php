<?php

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Arr;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user account created automatically at registration', function () {
    $email = fake()->safeEmail();
    $response = $this->post('/api/auth/users/register', [
        'first_name' => 'Saif',
        'last_name' => 'Shaikh',
        'handle' => 'SaifOmar',
        'email' => $email,
        'gender' => Arr::random(['male', 'female']),
        'longitude' => fake()->longitude(),
        'latitude' => fake()->latitude(),
        "accuracy_meters" => fake()->numberBetween(1, 15),
        'password' => 'password',
        "password_confirmation" => "password",
    ]);
    $response->assertStatus(201);
    $this->assertDatabaseHas('accounts', [
        'user_id' =>  User::where('email', $email)->first()->id
    ]);

    $account = Account::where('user_id', User::where('email', $email)->first()->id)->first();
    $handle = "@" . "saifomar";
    $this->assertEquals($account->handle, $handle);
});
