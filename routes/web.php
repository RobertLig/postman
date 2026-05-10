<?php

use Livewire\Volt\Volt;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/* Route::get(
    'senders-announcements/create',
    \App\Livewire\SendersAnnouncements\CreateSender::class
)->name('senders-announcements.create'); */

//🔐 3. AUTH ROUTES
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localeCookieRedirect',
        'localize',
        'auth',
        'verified',
        'auth.session'
    ]
], function () {

    Route::get('/senders/create', \App\Livewire\SendersAnnouncements\CreateSender::class)
        ->defaults('type', 'sender')
        ->name('senders-announcements.create');

    /* Route::get(
        LaravelLocalization::transRoute('routes.senders-announcements-create'),
        \App\Livewire\SendersAnnouncements\CreateSender::class
    )->name('senders-announcements.create'); */
});

//🌍 2. PUBLIC ROUTES (no auth)
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localeCookieRedirect',
        'localize',
    ]
], function () {

    Volt::route('/', 'index')->name('home');

    Volt::route(LaravelLocalization::transRoute('routes.about'), 'about')->name('about');
    Volt::route(LaravelLocalization::transRoute('routes.faq'), 'faq')->name('faq');
    Volt::route(LaravelLocalization::transRoute('routes.contact'), 'contact')->name('contact');

    Volt::route(LaravelLocalization::transRoute('routes.terms-of-use'), 'terms-of-use')->name('terms-of-use');
    Volt::route(LaravelLocalization::transRoute('routes.privacy-policy'), 'privacy-policy')->name('privacy-policy');
    Volt::route(LaravelLocalization::transRoute('routes.cookie-policy'), 'cookie-policy')->name('cookie-policy');

    Route::get(
        LaravelLocalization::transRoute('routes.senders-announcements'),
        \App\Livewire\SendersAnnouncements\ShowAnnouncements::class
    )->name('senders-announcements.index');

    Volt::route(
        LaravelLocalization::transRoute('routes.senders-announcements-show'),
        'senders-announcements.show'
    )
        ->name('senders-announcements.show')
        ->middleware(\App\Http\Middleware\EnsureSenderAnnouncementExists::class);

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-announcements'),
        \App\Livewire\CouriersAnnouncements\ShowAnnouncements::class
    )->name('couriers-announcements.index');

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-announcements-show'),
        \App\Livewire\CouriersAnnouncements\Show::class
    )->name('couriers-announcements.show');
});

//🔐 3. AUTH ROUTES
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localeCookieRedirect',
        'localize',
        'auth',
        'verified',
        'auth.session'
    ]
], function () {

    Route::get('/senders/{sender}/edit', \App\Livewire\SendersAnnouncements\CreateSender::class)
        ->defaults('type', 'sender')
        ->name('senders-announcements.edit');
    //->middleware(['can:update,sender']);

    /* Route::get(
        LaravelLocalization::transRoute('routes.senders-announcements-edit'),
        \App\Livewire\SendersAnnouncements\CreateSender::class
    )
        ->name('senders-announcements.edit')
        ->middleware(['can:update,sender']); */

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-announcements-create'),
        \App\Livewire\CouriersAnnouncements\Create::class
    )->name('couriers-announcements.create');

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-announcements-edit'),
        \App\Livewire\CouriersAnnouncements\Edit::class
    )
        ->name('couriers-announcements.edit')
        ->middleware('can:update,courier');
});

//🔑 4. AUTH / LOGIN GROUP
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localeCookieRedirect',
        'localize',
        'guest'
    ]
], function () {

    Volt::route(LaravelLocalization::transRoute('routes.register'), 'auth.register')->name('register');
    Volt::route(LaravelLocalization::transRoute('routes.login'), 'auth.login')->name('login');

    Volt::route(LaravelLocalization::transRoute('routes.forgot-password'), 'auth.forgot-password')
        ->name('password.request');

    Volt::route(LaravelLocalization::transRoute('routes.reset-password'), 'auth.reset-password')
        ->name('password.reset');
});

//⚙️ 5. VERIFIED + USER ROUTES
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localeCookieRedirect',
        'localize',
        'auth',
        'auth.session'
    ]
], function () {

    Volt::route(LaravelLocalization::transRoute('routes.verify-email'), 'auth.verify-email')
        ->name('verification.notice');

    Route::get(
        LaravelLocalization::transRoute('routes.verify-email-handler'),
        \App\Http\Controllers\Auth\VerifyEmailController::class
    )
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route(LaravelLocalization::transRoute('routes.confirm-password'), 'auth.confirm-password')
        ->name('password.confirm');

    Volt::route(LaravelLocalization::transRoute('routes.messages'), 'messages')
        ->middleware(['verified'])
        ->name('messages');

    Route::get(
        LaravelLocalization::transRoute('routes.chat'),
        \App\Livewire\Chat::class
    )
        ->middleware([
            'verified',
            'can:talk,user',
            'can:talkAboutAnnouncement,user'
        ])
        ->name('chat');
});

//⚙️ 6. SETTINGS
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localeCookieRedirect',
        'localize',
        'auth',
        'verified',
        'auth.session'
    ]
], function () {

    Route::redirect(
        LaravelLocalization::transRoute('routes.settings'),
        LaravelLocalization::transRoute('routes.settings-profile')
    );

    Volt::route(LaravelLocalization::transRoute('routes.settings-password'), 'settings.password')
        ->name('settings.password');

    Volt::route(LaravelLocalization::transRoute('routes.settings-profile'), 'settings.profile')
        ->name('settings.profile');
});

/* use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\VerifyEmail; //not used in application, doesn't work
use App\Http\Controllers\Auth\LogoutController; //not used in application, but works
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Middleware\EnsureUserCanEditSenderAnnouncement;
use App\Http\Middleware\EnsureUserCanEditCourierAnnouncement;
use App\Http\Middleware\EnsureSenderAnnouncementExists;
use App\Http\Middleware\EnsureCourierAnnouncementExists;
//use App\Livewire\SendersAnnouncements\ShowAnnouncements; //problem
use App\Livewire\Chat; //ChatMessage
use \Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Livewire\CouriersAnnouncements\Create;
use App\Livewire\CouriersAnnouncements\Edit;
use App\Livewire\CouriersAnnouncements\Show;
use App\Livewire\CouriersAnnouncements\ShowAnnouncements;
use Livewire\Livewire;

//The sequence of the route definition has a meaning
Route::group([
    'prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localeCookieRedirect', 'localize', 'auth', 'verified', 'auth.session']
], function () {
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements-create'), 'senders-announcements.create')->name('senders-announcements.create');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements-edit'), 'senders-announcements.edit')
        ->name('senders-announcements.edit')->middleware(EnsureUserCanEditSenderAnnouncement::class); //->middleware(EnsureUserCanEditSenderAnnouncement::class.':senderannouncement') ->can('update', 'senderannouncement') ->middleware('can:update,senderannouncement')  can midleware doesn't work

    Route::get(LaravelLocalization::transRoute('routes.couriers-announcements-create'), Create::class)
        ->name('couriers-announcements.create');

    Route::get(LaravelLocalization::transRoute('routes.couriers-announcements-edit'), Edit::class)
        ->name('couriers-announcements.edit')->can('update', 'courier'); //->middleware(EnsureUserCanEditCourierAnnouncement::class)
});

Route::group([
    'prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localeCookieRedirect', 'localize']
], function () {
    Volt::route('/', 'index')->name('home');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.users'), 'users.index')->name('users.index');

    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.about'), 'about')->name('about');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.faq'), 'faq')->name('faq');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.contact'), 'contact')->name('contact');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.terms-of-use'), 'terms-of-use')->name('terms-of-use');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.privacy-policy'), 'privacy-policy')->name('privacy-policy');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.cookie-policy'), 'cookie-policy')->name('cookie-policy');

    Route::get(
        \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements'),
        App\Livewire\SendersAnnouncements\ShowAnnouncements::class
    )->name('senders-announcements.index');

    //Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements'), 'senders-announcements.index')->name('senders-announcements.index');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements-show'), 'senders-announcements.show')
        ->name('senders-announcements.show')->middleware(EnsureSenderAnnouncementExists::class);

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-announcements'),
        ShowAnnouncements::class
    )->name('couriers-announcements.index');

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-announcements-show'),
        Show::class
    )->name('couriers-announcements.show');

}); */

/*Route::group([
    'prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localeCookieRedirect', 'localize', 'guest']
], function () {
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.register'), 'auth.register')->name('register');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.login'), 'auth.login')->name('login');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.forgot-password'), 'auth.forgot-password')->name('password.request');
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.reset-password'), 'auth.reset-password')->name('password.reset');
});

Route::group([
    'prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localeCookieRedirect', 'localize', 'auth', 'auth.session']
], function () {
    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.verify-email'), 'auth.verify-email')
        ->name('verification.notice');

    Route::get(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.verify-email-handler'), VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.confirm-password'), 'auth.confirm-password')
        ->name('password.confirm');

    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.messages'), 'messages')
        ->middleware(['verified']) //, 'password.confirm'
        ->name('messages');

    Route::get(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.chat'), Chat::class) //ChatMessage
        ->middleware(['verified'])
        ->name('chat')->middleware(['can:talk,user', 'can:talkAboutAnnouncement,user']);
});

Route::group([
    'prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localeCookieRedirect', 'localize', 'auth', 'verified', 'auth.session']
], function () {
    Route::redirect(
        \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.settings'),
        \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.settings-profile')
    );

    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.settings-password'), 'settings.password')
        ->name('settings.password');

    Volt::route(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.settings-profile'), 'settings.profile')
        ->name('settings.profile');
}); */

/* Route::get('/mailable', function () {
    return new App\Mail\ContactMailable('Robert Ligęza', 'robertligeza2@gmail.com', 'How to do this?'); //test
}); */

Route::group(['prefix' => LaravelLocalization::setLocale()], function () { //original
    // Your other localized routes...

    Livewire::setUpdateRoute(function ($handle) {
        $locale = Illuminate\Support\Facades\App::currentLocale();

        return Route::post("/{$locale}/livewire/update", $handle);
    });
}); 


/* Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['web', 'localize']
], function () {
    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });
}); */
