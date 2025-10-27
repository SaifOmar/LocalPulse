<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Helpers\Helpers;
use App\Models\Account;
use App\Models\Comment;
use App\Models\User;
use App\Models\Like;
use App\Models\Mood;
use App\Models\Pulse;

describe("User can like pulses", function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->account = Account::create([
            'user_id' => $this->user->id,
            'handle' => '@saifomar',
            'password' => Hash::make('password'),
        ]);
        Mood::factory()->create();
        $this->pulse = Pulse::create([
            'account_id' => $this->account->id,
            'caption' => 'test',
            'mood_id' => 1,
            'type' => 'image',
            // 'media' =>  UploadedFile::fake()->image('avatar.png', 1024, 1024)->size(1024),
        ]);
        $token = Helpers::createUserToken($this->user, $this->account->handle);
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);
    });
    it('can like pulse', function () {
        $response = $this->postJson('/api/likes', [
            'liked_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'liked_type' => 'pulse',
        ]);
        $response->assertStatus(201);
    });

    it('can like comment', function () {
        $comment = Comment::create([
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'content' => 'test',
            'type' => 'comment_comment',
        ]);
        $response = $this->postJson('/api/likes', [
            'account_id' => $this->account->id,
            'liked_type' => 'comment',
            'liked_id' => $comment->id,
        ]);
        $response->assertStatus(201);

    });
    it('can dislike obj', function () {
        $like = Like::create([
            'liked_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'liked_type'=> 'pulse',
        ]);
        $response = $this->deleteJson('/api/likes', [
            'liked_id' => $this->pulse->id,
            'liked_type' => 'pulse',
        ]);
        $response->assertStatus(204);
    });
});
