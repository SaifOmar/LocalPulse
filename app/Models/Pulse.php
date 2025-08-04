<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pulse extends Model
{
    //
    public function user(): User
    {
        return $this->account()->first()->user;
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    public function comments()
    {
    }
    public function likes()
    {
    }
}
