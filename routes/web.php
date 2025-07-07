<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::group(['prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
                          'middleware' => [ 'localeSessionRedirect', 'localeCookieRedirect', 'localize' ]], function()
{
   Volt::route('/', 'index')->name('home');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.users'), 'users.index')->name('users.index');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.register'), 'auth.register')->name('register');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.login'), 'auth.login')->name('login');
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.messages'), 'messages')->name('messages');
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
});

Route::group(['prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
                          'middleware' => [ 'localeSessionRedirect', 'localeCookieRedirect', 'localize', 'auth' ]], function()
{
   Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.verify-email'), 'auth.verify-email')
       ->name('verification.notice');

   Route::get(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.verify-email-handler'), VerifyEmailController::class)
       ->middleware('signed')
       ->name('verification.verify');
});

/*Route::middleware('auth')->group(function() {
   Volt::route('verify-email', 'auth.verify-email')
       ->name('verification.notice');

   Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
       ->middleware('signed')
       ->name('verification.verify');
});*/