<?php

use Illuminate\Support\Facades\Route;

Route::get('/reset-password/{token}', function ($token) {
    return view('reset-password', ['token' => $token]);
});

Route::get('/', function () {
    return view('welcome');
});
