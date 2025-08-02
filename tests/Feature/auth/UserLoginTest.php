<?php

use App\Models\Account;
use App\Models\User;
use Symfony\Component\HttpFoundation\ParameterBag;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test("user can login with valid credentials(email)", function () {
    $user = User::factory()->create();
    $account = Account::create([
        "user_id" => $user->id,
        'handle' => "@saifomar",
    ]);
    $response = $this->postJson("/api/auth/users/login", [
        "identifier" => $user->email,
        "password" => "password",
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure(["success", "access"]);
});


test("user can login with valid credentials(handle)", function () {
    $user = User::factory()->create();
    $account = Account::create([
        "user_id" => $user->id,
        'handle' => "@saifomar",
    ]);
    $response = $this->postJson("/api/auth/users/login", [
        "identifier" => $account->handle,
        "password" => "password",
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure(["success", "access"]);
});
test("user can login with invalid credentials", function () {
    $user = User::factory()->create();
    $response = $this->postJson("/api/auth/users/login", [
        "identifier" => $user->email,
        "password" => "wrong-password",
    ]);
    $response->assertStatus(422);
    $response->assertJson(["message" => "The provided credentials are incorrect.", "errors" => ["error" => ["The provided credentials are incorrect."]]]);
});
test("rate limitid users can't loign", function () {
    $user = User::factory()->create();
    $account = Account::create([
        "user_id" => $user->id,
        'handle' => "@saifomar",
    ]);
    for ($i = 0; $i < 5; $i++) {
        $response = $this->postJson("/api/auth/users/login", [
            "identifier" => $account->handle,
            "password" => "wrong-password",
        ]);
    }

    $response = $this->postJson("/api/auth/users/login", [
        "identifier" => $account->handle,
        "password" => "wrong-password",
    ]);
    $response->assertStatus(422);
    // $response->assertSee('Too many login attempts. Please try again in 59 seconds.');
});
