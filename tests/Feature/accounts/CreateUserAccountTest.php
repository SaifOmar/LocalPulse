<?php

use App\Helpers\Helpers;
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

it('auth user can create another account', function () {
    $email = fake()->safeEmail();
    $gender = Arr::random(['male', 'female']);
    $response = $this->post('/api/auth/users/register', [
        'first_name' => 'Saif',
        'last_name' => 'Shaikh',
        'handle' => 'SaifOmar',
        'email' => $email,
        'gender'=>$gender,
        'longitude' => fake()->longitude(),
        'latitude' => fake()->latitude(),
        "accuracy_meters" => fake()->numberBetween(1, 15),
        'password' => 'password',
        "password_confirmation" => "password",
    ]);
    $user = User::where('email', $email)->first();
    $token = $response->json('access');

    $response = $this->postJson('/api/auth/accounts', [
        'handle' => '@saifomar2',
        'password' => 'password',
        'gender' => $gender,
        'password_confirmation' => 'password',
        ], [
        'Authorization' => 'Bearer ' . $token,
    ]);
    $response->assertStatus(201);
    $this->assertDatabaseHas('accounts', [
        'user_id' =>  User::where('email', $email)->first()->id
    ]);

});
