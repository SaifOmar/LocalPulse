<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Account;

function createUri($id)
{
    return 'api/auth/accounts/' . $id . '/update';
}
//
test('user can update account specific data', function () {
    $user = User::factory()->create();

    $account = Account::create([
        'user_id' => $user->id,
        'handle' => 'SaifOmar',
        'password' => Hash::make('password'),
    ]);
    $token = $user->createToken('test-token'.$account->id);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token->plainTextToken,
    ])->putJson(
        createUri($account->id),
        [
            'handle' => 'dSaifOmar',
            'bio' => 'dI am a saif',
        ]
    );
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data'=> [
            'name',
            'handle',
            'email',
            'avatar',
        ]
    ]);
    $account = Account::find($account->id);
    // $this->assertEquals($account->user->first_name, 'dSaif');
    // $this->assertEquals($account->user->last_name, 'dShaikh');
    // $this->assertEquals($account->user->email, 'dsaif@gmail.com');
    $this->assertEquals($account->bio, 'dI am a saif');
    $this->assertEquals($account->handle, '@dsaifomar');
});
test('user can update user specific data', function () {
    $user = User::factory()->create();

    $account = Account::create([
        'user_id' => $user->id,
        'handle' => 'SaifOmar',
        'password' => Hash::make('password'),
    ]);


    $token = $user->createToken('test-token'.$account->id);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token->plainTextToken,
    ])-> putJson(
        createUri($account->id),
        [
            'first_name' => 'dSaif',
            'last_name' => 'dShaikh',
            'email' => 'dsaif@gmail.com',
        ]
    );

    $response->assertStatus(200);
    // $response->assertJsonStructure([
    //     'first_name',
    //     'last_name',
    //     'email',
    // ]);
    $account = Account::find($account->id);
    $this->assertEquals($account->user->first_name, 'dSaif');
    $this->assertEquals($account->user->last_name, 'dShaikh');
    $this->assertEquals($account->user->email, 'dsaif@gmail.com');
});
test('user can update mixed data', function () {
    $user = User::factory()->create();

    $account = Account::create([
        'user_id' => $user->id,
        'handle' => 'SaifOmar',
        'password' => Hash::make('password'),
    ]);

    $token = $user->createToken('test-token'.$account->id);
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token->plainTextToken,
    ])->putJson(
        createUri($account->id),
        [
            'first_name' => 'dSaif',
            'last_name' => 'dShaikh',
            'email' => 'dsaif@gmail.com',
            'bio' => 'dI am a saif',
            'gender' => 'male',
        ],
    );
    $response->assertStatus(200);
    // dd($response->json());
    $response->assertJsonStructure([
        'data' => [
            'name',
            'handle',
            'avatar',
        ]
     ]);

    $account = Account::find($account->id);
    $this->assertEquals($account->user->first_name, 'dSaif');
    $this->assertEquals($account->user->last_name, 'dShaikh');
    $this->assertEquals($account->user->email, 'dsaif@gmail.com');
    $this->assertEquals($account->bio, 'dI am a saif');
    $this->assertEquals($account->gender, 'male');
});
test("user can't update with a taken handle", function () {
    $user = User::factory()->create();
    $account = Account::create([
        'user_id' => $user->id,
        'handle' => '@saifomar',
        'password' => Hash::make('password'),
    ]);
    $account2 = Account::create([
        'user_id' => $user->id,
        'handle' => '@saifomar2',
        'password' => Hash::make('password'),
    ]);

    $token = $user->createToken('test-token'.$account->id);
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token->plainTextToken,
    ])->putJson(
        createUri($account2->id),
        [
            'first_name' => 'dSaif',
            'last_name' => 'dShaikh',
            'email' => 'dsaif@gmail.com',
            'handle' => 'SaifOmar',
            'bio' => 'dI am a saif',
            'gender' => 'male',
        ],
    );

    $response->assertStatus(422);
});

test("test auth test", function () {
    $user = User::factory()->create();
    $account = Account::create([
        'user_id' => $user->id,
        'handle' => 'SaifOmar',
        'password' => Hash::make('password'),
    ]);
    $token = $user->createToken('test-token-'.$account->id);
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token->plainTextToken,
    ])->getJson('api/test/auth');
    $response->assertStatus(200);
})->skip();
