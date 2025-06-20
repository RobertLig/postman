<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::group(['prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
                          'middleware' => [ 'localeSessionRedirect', 'localeCookieRedirect', 'localize' ]], function()
{
   Volt::route('/', 'users.index')->name('home');
   Volt::route('/register', 'auth.register')->name('register');
   Volt::route('/login', 'auth.login')->name('login');
});