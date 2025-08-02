<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test("user can register with valid credentials", function () {
    $response = $this->postJson("/api/auth/users/register", [
        "email" => "saifs@gmail.com",
        "first_name" => "Saif",
        "last_name" => "Omar",
        'handle' => 'SaifOmar',
        "password" => "password",
        "password_confirmation" => "password",
    ]);
    $response->assertStatus(201);
    $response->assertJsonStructure([
        "first_name",
        "last_name",
        "email",
        "access"
    ]);
});

test("user can not register with invalid credentials", function () {
    $response = $this->postJson("/api/auth/users/register", [
        "email" => "saifs@gmail.com",
        "first_name" => "Saif",
        "last_name" => "Omar",
        "password" => "password",
        "password_confirmation" => "wrong-password",
    ]);
    $response->assertStatus(422);
});
