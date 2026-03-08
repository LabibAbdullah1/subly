<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin dashboard overview
// We will generate an Admin Controller later
