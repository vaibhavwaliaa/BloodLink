<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/requests/new', 'requests.create')->name('requests.create');
Route::view('/oauth/callback', 'oauth.callback')->name('oauth.callback');
Route::view('/register-donor', 'donor.registration')->name('donor.registration');
