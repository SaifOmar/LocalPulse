<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interaction extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pulse(): BelongsTo
    {
        return $this->belongsTo(Pulse::class);
    }
    public function casts(): array
    {
        return [
            'delta' => 'integer',
            'meta' => 'json',
        ];
    }
}
