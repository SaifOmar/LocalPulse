<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test("user can login with valid credentials", function () {
    $user = User::factory()->create();
    $response = $this->postJson("/api/users/login", [
        "identifier" => $user->email,
        "password" => "password",
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure(["success", "access"]);
});

test("user can login with invalid credentials", function () {
    $user = User::factory()->create();
    $response = $this->postJson("/api/users/login", [
        "identifier" => $user->email,
        "password" => "wrong-password",
    ]);
    $response->assertStatus(422);
    $response->assertJson(["message" => "The provided credentials are incorrect.", "errors" => ["error" => ["The provided credentials are incorrect."]]]);
});
