<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/udash', function () {
    return view('user.dashboard');
});

Route::get('/adash', function () {
    return view('admin.dashboard');
});

Route::get('/aadd', function () {
    return view('admin.add');
});

Route::get('/adetail', function () {
    return view('admin.detail');
});

Route::get('/login', function () {
    return view('login');
});