<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\VerifyEmail; //not used in application, doesn't work
use App\Http\Controllers\Auth\LogoutController; //not used in application, but works
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::group(['prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
                          'middleware' => [ 'localeSessionRedirect', 'localeCookieRedirect', 'localize' ]], function()
{
   Volt::route('/', 'index')->name('home');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.users'), 'users.index')->name('users.index');
   
   #Volt::route('/settings', 'settings')->name('settings');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.about'), 'about')->name('about');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.faq'), 'faq')->name('faq');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.contact'), 'contact')->name('contact');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.terms-of-use'), 'terms-of-use')->name('terms-of-use');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.privacy-policy'), 'privacy-policy')->name('privacy-policy');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.cookie-policy'), 'cookie-policy')->name('cookie-policy');
   Volt::route('/settings/wifi', 'settings')->name('settings.wifi'); #componentName should be settings.wifi
   Volt::route('/settings/archive', 'settings')->name('settings.archive'); #componentName should be settings.archive
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements'), 'senders-announcements.index')->name('senders-announcements.index');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements-create'), 'senders-announcements.create')->name('senders-announcements.create');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements-edit'), 'senders-announcements.edit')->name('senders-announcements.edit');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements-show'), 'senders-announcements.show')->name('senders-announcements.show');

   /*Route::post('logout', [LogoutController::class, 'logout'])
      ->name('logout'); */
});

Route::group(['prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
                          'middleware' => [ 'localeSessionRedirect', 'localeCookieRedirect', 'localize', 'guest' ]], function()
{
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.register'), 'auth.register')->name('register');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.login'), 'auth.login')->name('login');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.forgot-password'), 'auth.forgot-password')->name('password.request');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.reset-password'), 'auth.reset-password')->name('password.reset');
});

Route::group(['prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
                          'middleware' => [ 'localeSessionRedirect', 'localeCookieRedirect', 'localize', 'auth', 'auth.session' ]], function()
{
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.verify-email'), 'auth.verify-email')
       ->name('verification.notice');

    Route::get(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.verify-email-handler'), VerifyEmailController::class)
       ->middleware(['signed', 'throttle:6,1'])
       ->name('verification.verify');

    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.messages'), 'messages')
       ->middleware('verified')
       ->name('messages');   
});


