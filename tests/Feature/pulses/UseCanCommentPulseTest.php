<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Account;
use App\Models\User;
use App\Models\Pulse;
use App\Models\Comment;

describe("User can comment pulses", function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->account = Account::create([
            'user_id' => $this->user->id,
            'handle' => '@saifomar',
            'password' => Hash::make('password'),
        ]);
        $this->pulse = Pulse::create([
            'account_id' => $this->account->id,
            'caption' => 'test',
            'type' => 'image',
            // 'media' =>  UploadedFile::fake()->image('avatar.png', 1024, 1024)->size(1024),
        ]);
    });
    it('can comment on pulse', function () {
        $response = $this->postJson('/api/comments/store', [
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'content' => 'test',
            'type' => 'comment_comment',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'pulse_id',
            'account_id',
            'type'
        ]);
    });
    it('can delete pulse comment ', function () {
        $comment = Comment::create([
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'content' => 'test',
            'type' => 'comment_comment',
        ]);
        $response = $this->deleteJson('/api/comments/'. $comment->id. '/delete');
        $response->assertStatus(200);
    });
});
