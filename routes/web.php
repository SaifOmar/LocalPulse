<?php

use App\Models\Image;
use App\Models\Mood;
use App\Models\Pulse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/moods", function () {
    return Mood::create(
        [
            'name' => 'happy',
            'icon' => "smile"
        ]
    );
});
