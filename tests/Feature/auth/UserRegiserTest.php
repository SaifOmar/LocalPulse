<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe("user register", function () {
    beforeEach(function () {
        $this->longitude = fake()->longitude();
        $this->latitude = fake()->latitude();
        // dd($this->longitude, $this->latitude);
        $this->accuracy_meters = random_int(1, 15);
        $this->essentials = [
            "first_name" => "Saif",
            "last_name" => "Omar",
            "email" => "saifs@gmail.com",
            'handle' => 'SaifOmar',
            "password" => "password",
            "gender" => Arr::random(["male", "female"]),
            "password_confirmation" => "password",
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            "accuracy_meters" => $this->accuracy_meters,
        ];
    });

    it("user can register with valid credentials", function () {
        $response = $this->postJson("/api/auth/users/register", [
            ...$this->essentials,
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            "first_name",
            "last_name",
            "email",
            "access",
            'longitude',
            'latitude',
        ]);
    });

    it("user can not register with invalid credentials", function () {
        $data = $this->essentials;
        unset($data['password_confirmation']);
        $response = $this->postJson("/api/auth/users/register", [
            ...$data,
            "password_confirmation" => "wrong-password",
        ]);
        $response->assertStatus(422);
    });

    it("user can register with avatar image", function () {
        $response = $this->postJson("/api/auth/users/register", [
            ...$this->essentials,
            "avatar" => UploadedFile::fake()->image('avatar.png', 1024, 1024)->size(1024),
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            "first_name",
            "last_name",
            "email",
            "access",
            'data' => [
                'name',
                'email',
                'city',
                'handle',
                'avatar'
            ]
        ]);
    });
});
