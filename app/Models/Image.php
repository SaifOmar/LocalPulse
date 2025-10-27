<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function pulse()
    {
        return $this->belongsTo(Pulse::class);
    }
}
