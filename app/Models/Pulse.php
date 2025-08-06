<?php

namespace App\Models;

use App\Policies\PulsePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(PulsePolicy::class)]
class Pulse extends Model
{
    use HasFactory;
    //
    public function user(): User
    {
        return $this->account()->first()->user;
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'pulse_tags');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
