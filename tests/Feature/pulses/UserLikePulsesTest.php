<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Account;
use App\Models\Comment;
use App\Models\User;
use App\Models\Pulse;
use Illuminate\Http\UploadedFile;

describe("User can like pulses", function () {
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
    it('can like pulse', function () {
        $response = $this->postJson('/api/likes/store', [
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'type' => 'pulse_like',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'pulse_id',
            'account_id',
            'type'
        ]);
    });

    it('can like comment', function () {
        $comment = Comment::create([
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'content' => 'test',
            'type' => 'comment_comment',
        ]);
        $response = $this->postJson('/api/likes/store', [
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'type' => 'comment_like',
            'comment_id' => $comment->id,
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'pulse_id',
            'account_id',
            'type'
        ]);

    });
    // this is because route is not a toggle
    it("can't like comment twice", function () {
        $comment = Comment::create([
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'content' => 'test',
            'type' => 'comment_comment',
        ]);
        $response = $this->postJson('/api/likes/store', [
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'type' => 'comment_like',
            'comment_id' => $comment->id,
        ]);
        $response->assertStatus(201);
        $response = $this->postJson('/api/likes/store', [
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'type' => 'comment_like',
            'comment_id' => $comment->id,
        ]);
        $response->assertStatus(422);
    });
    it('can dislike pulse', function () {
        $response = $this->deleteJson('/api/likes/.$this->pulse->id./delete', [
            'pulse_id' => $this->pulse->id,
            'account_id' => $this->account->id,
            'type' => 'pulse_dislike',
        ]);
        $response->assertStatus(200);
    });
});
