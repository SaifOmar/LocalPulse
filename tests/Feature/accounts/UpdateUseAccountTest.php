<?php
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Account;

function createUri($id)
{
    return 'api/auth/accounts/' . $id . '/update';
}

test('user can update account specific data', function () {
    $user = User::factory()->create();

    $account = Account::create([
        'user_id' => $user->id,
        'handle' => 'SaifOmar',
    ]);

    $response = $this->putJson(
        createUri($account->id),
        [
            // 'first_name' => 'dSaif',
            // 'last_name' => 'dShaikh',
            // 'email' => 'dsaif@gmail.com',
            'handle' => 'dSaifOmar',
            'bio' => 'dI am a saif',

        ]
    );
    $response->assertStatus(200);
    $response->assertJson([
        // 'user' => [
        //     'first_name' => 'dSaif',
        //     'last_name' => 'dShaikh',
        //     'email' => 'dsaif@gmail.com',
        // ],
        'handle' => '@dsaifomar',
        'bio' => 'dI am a saif',
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
    ]);

    $response = $this->putJson(
        createUri($account->id),
        [
            'first_name' => 'dSaif',
            'last_name' => 'dShaikh',
            'email' => 'dsaif@gmail.com',

        ]
    );
    $response->assertStatus(200);
    $response->assertJson([
        'user' => [
            'first_name' => 'dSaif',
            'last_name' => 'dShaikh',
            'email' => 'dsaif@gmail.com',
        ],
    ]);
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
    ]);

    $response = $this->putJson(
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
    $response->assertJson([
        'bio' => 'dI am a saif',
        'gender' => 'male',
        'user' => [
            'first_name' => 'dSaif',
            'last_name' => 'dShaikh',
            'email' => 'dsaif@gmail.com',
        ],
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
    ]);
    $account2 = Account::create([
        'user_id' => $user->id,
        'handle' => '@saifomar2',
    ]);

    $response = $this->putJson(
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
