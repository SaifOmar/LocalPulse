<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function pulses()
    {
        return $this->belongsToMany(Pulse::class, 'pulse_tags');
    }
}
