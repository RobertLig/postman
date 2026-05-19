<?php

use Livewire\Volt\Volt;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Livewire\Announcement\FormAnnouncement;
use App\Livewire\Announcement\ShowAnnouncements;
use App\Livewire\Announcement\Show;
use App\Http\Controllers\PlaceAutocompleteController;

use App\Livewire\Conversations\IndexConversations;
use App\Livewire\Conversations\ShowConversation;
use App\Livewire\Conversations\ShowConversationExisting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use App\Livewire\Admin\Testimonials\Form;
use App\Livewire\Admin\Testimonials\Index;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get(
            '/testimonials',
            Index::class
        )->name('testimonials.index');

        Route::get(
            '/testimonials/create',
            Form::class
        )->name('testimonials.create');

        Route::get(
            '/testimonials/{testimonial}/edit',
            Form::class
        )->name('testimonials.edit');
    });

Route::post('/cookie-consent/reset', function () {

    Cookie::queue(Cookie::forget('analytics_consent'));

    Cookie::queue(Cookie::forget('google_ads_consent'));

    Cookie::queue(Cookie::forget('consent_answered'));

    return response()->json([
        'success' => true,
    ]);
});

Route::post('/cookie-consent', function (Request $request) {

    Cookie::queue(
        'analytics_consent',
        $request->boolean('analytics') ? 'true' : 'false',
        60 * 24 * 365
    );

    Cookie::queue(
        'google_ads_consent',
        $request->boolean('google_ads') ? 'true' : 'false',
        60 * 24 * 365
    );

    Cookie::queue(
        'consent_answered',
        'true',
        60 * 24 * 365
    );

    return response()->json([
        'success' => true,
    ]);
});

Route::get(
    '/place-autocomplete',
    PlaceAutocompleteController::class
)->name('place-autocomplete');

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

    Route::get(
        LaravelLocalization::transRoute('routes.senders-create'),
        FormAnnouncement::class
    )
        ->defaults('type', 'sender')
        ->name('senders.create');

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-create'),
        FormAnnouncement::class
    )
        ->defaults('type', 'courier')
        ->name('couriers.create');
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
        LaravelLocalization::transRoute('routes.senders'),
        ShowAnnouncements::class
    )
        ->defaults('type', 'sender')
        ->name('senders');

    Route::get(
        LaravelLocalization::transRoute('routes.senders-show'),
        Show::class
    )
        ->defaults('type', 'sender')
        ->name('senders.show');

    Route::get(
        LaravelLocalization::transRoute('routes.couriers'),
        ShowAnnouncements::class
    )
        ->defaults('type', 'courier')
        ->name('couriers');

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-show'),
        Show::class
    )
        ->defaults('type', 'courier')
        ->name('couriers.show');
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

    Route::get(
        LaravelLocalization::transRoute('routes.senders-edit'),
        FormAnnouncement::class
    )
        ->defaults('type', 'sender')
        ->name('senders.edit');
    //->middleware(['can:update,sender']);

    Route::get(
        LaravelLocalization::transRoute('routes.couriers-edit'),
        FormAnnouncement::class
    )
        ->defaults('type', 'courier')
        ->name('couriers.edit');
    //->middleware('can:update,courier');

    Route::get(
        LaravelLocalization::transRoute('routes.conversations'),
        IndexConversations::class
    )->name('conversations.index');

    Route::get(
        LaravelLocalization::transRoute('routes.conversations-show'),
        ShowConversation::class
    )
        ->whereIn('type', ['sender', 'courier'])
        ->whereNumber('announcement')
        ->name('conversations.show');

    Route::get(
        LaravelLocalization::transRoute('routes.conversations-existing'),
        ShowConversationExisting::class
    )
        ->whereNumber('conversation')
        ->name('conversations.show.existing');
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
