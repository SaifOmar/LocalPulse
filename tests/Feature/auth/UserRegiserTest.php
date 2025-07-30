<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test("user can register with valid credentials", function () {
    $response = $this->postJson("/api/users/register", [
        "email" => "saifs@gmail.com",
        "name" => "Saif",
        "password" => "password",
        "password_confirmation" => "password",
    ]);
    $response->assertStatus(201);
    $response->assertJsonStructure([
        "name",
        "email",
        "access"
    ]);
});

test("user can not register with invalid credentials", function () {
    $response = $this->postJson("/api/users/register", [
        "email" => "saifs@gmail.com",
        "name" => "Saif",
        "password" => "password",
        "password_confirmation" => "wrong-password",
    ]);
    $response->assertStatus(422);
});
