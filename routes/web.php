<?php

use Livewire\Volt\Volt;

Volt::route('/', 'users.index');
Volt::route('/register', 'auth.register');
Volt::route('/login', 'auth.login');
