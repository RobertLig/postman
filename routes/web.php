<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::group(['prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
                          'middleware' => [ 'localeSessionRedirect', 'localeCookieRedirect', 'localize' ]], function()
{
   Volt::route('/', 'users.index')->name('home');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.register'), 'auth.register')->name('register');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.login'), 'auth.login')->name('login');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.messages'), 'messages')->name('messages');
   #Volt::route('/settings', 'settings')->name('settings');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.about'), 'about')->name('about');
   Volt::route('/settings/wifi', 'settings')->name('settings.wifi'); #componentName should be settings.wifi
   Volt::route('/settings/archive', 'settings')->name('settings.archive'); #componentName should be settings.archive
   Volt::route('/senders-announcements', 'senders-announcements.index')->name('senders-announcements.index');
});