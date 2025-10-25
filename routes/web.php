<?php

use App\Models\Image;
use App\Models\Mood;
use App\Models\Pulse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

// Route::get("/moods", function () {
//     $pulse = Pulse::find(3);
//
//
//     return $pulse->url;
// });
