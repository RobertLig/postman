<?php

use Livewire\Volt\Volt;

Volt::route('/', 'users.index')->name('home');
Volt::route('/register', 'auth.register')->name('register');
Volt::route('/login', 'auth.login')->name('login');
