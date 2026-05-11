<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="{{ $metaDescription ?? __('This service aims to connect people who want to send a package with those who are traveling there. You can post ads in two categories: as a sender or as a courier . As a sender, which means someone who wants to to send something somewhere and is looking for a courier. As a courier, which means someone who is traveling somewhere and can deliver something to someone there.') }}">
    <title>{{ isset($title) ? __($title) . ' - ' . config('app.name') : config('app.name') }}</title>
    {{-- <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}"> --}}
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}"> --}}
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @cookieconsentscripts

    {{-- @cookieconsentCategoryEnabled('google-ads')
        <!-- Global site tag (gtag.js) - Google Ads -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17861754370"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            window.gtag = gtag;
            gtag('js', new Date());
            gtag('config', 'AW-17861754370');
        </script> 
    @endcookieconsentCategoryEnabled --}}

    @if (\Whitecube\LaravelCookieConsent\Facades\Cookies::hasConsentFor('Google Ads'))
        <!-- Global site tag (gtag.js) - Google Ads -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17861754370"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            window.gtag = gtag;
            gtag('js', new Date());
            gtag('config', 'AW-17861754370');
        </script>
    @endif

    <!-- TrustBox script -->
    <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
    <!-- End TrustBox script -->
</head>

<body class="font-sans antialiased">
    @php
        #for active buttons
        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
        $register = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.register');
        $login = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.login');
        $messages = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.messages');
        $about = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.about');
        $sendersAnnouncements = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute(
            'routes.senders-announcements',
        );
        $couriersAnnouncements = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute(
            'routes.couriers-announcements',
        );
    @endphp
    {{-- The navbar with `sticky` and `full-width` --}}
    <x-nav sticky full-width>

        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>

            {{-- Brand --}}
            {{-- <div>Postman</div> --}}
            <x-app-brand />
        </x-slot:brand>

        {{-- Right side actions --}}
        <x-slot:actions>
            {{-- theme toggle --}}
            <x-theme-toggle darkTheme="synthwave" lightTheme="caramellatte" class="ms-2 sm:ms-0" />
            {{--  --}}
            {{-- language selector --}}
            <x-dropdown-select-reload
                label="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() }}"
                class="btn-sm">
                <x-slot:icon>
                    <x-icon name="o-chevron-down" class="w-4 h-4" />
                    <!-- <x-icons.chevron-down /> -->
                </x-slot>
                @foreach (\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <li>
                        <a rel="alternate" hreflang="{{ $localeCode }}"
                            href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            {{ $properties['native'] }}
                        </a>
                    </li>
                @endforeach
            </x-dropdown-select-reload>

            @if (!auth()->user())
                <x-button label="{{ __('Register') }}" icon="o-pencil-square"
                    link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/register') }}"
                    class="btn-ghost btn-sm" responsive @class([
                        'btn-ghost btn-sm',
                        'bg-neutral border-neutral :hover:bg-neutral text-neutral-content shadow-none' => request()->is(
                            $locale . '/' . $register),
                    ]) /> {{-- $locale.'/register' --}}
                <x-button label="{{ __('Login') }}" icon="o-arrow-right-end-on-rectangle"
                    link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/login') }}"
                    class="btn-ghost btn-sm" responsive @class([
                        'btn-ghost btn-sm',
                        'bg-neutral border-neutral :hover:bg-neutral text-neutral-content shadow-none' => request()->is(
                            $locale . '/' . $login),
                    ]) />
            @else
                <livewire:settings.dropdown-login />
            @endif
        </x-slot:actions>
    </x-nav>

    {{-- The main content with `full-width` --}}
    <x-main with-nav full-width> {{--  full-width --}}

        {{-- This is a sidebar that works also as a drawer on small screens --}}
        {{-- Notice the `main-drawer` reference here --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200" collapse-text="{{ __('Collapse') }}">
            {{-- Activates the menu item when a route matches the `link` property --}}
            <x-menu> {{-- activate-by-route doesn' work with mcamara localization --}}
                <x-menu-item title="{{ __('Home') }}" icon="o-home" link="{{ route('home') }}"
                    {{-- \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/') --}} @class(['bg-neutral text-neutral-content' => request()->is($locale)]) /> {{-- bg-secondary-content --}}

                <x-menu-item title="{{ __('Senders` announcements') }}" icon="o-clipboard-document-list"
                    link="{{ route('senders-announcements.index') }}" @class([
                        'bg-neutral text-neutral-content' => request()->is(
                            $locale . '/' . $sendersAnnouncements . '*'),
                    ]) />

                <x-menu-item title="{{ __('Courier`s announcements') }}" icon="o-clipboard-document-list"
                    link="{{ route('couriers-announcements.index') }}" @class([
                        'bg-neutral text-neutral-content' => request()->is(
                            $locale . '/' . $couriersAnnouncements . '*'),
                    ]) />

                @if (auth()->user())
                    <x-menu-item title="{{ __('Messages') }}" icon="o-envelope" link="{{ route('messages') }}"
                        {{-- \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/messages') --}} @class([
                            'bg-neutral text-neutral-content' => request()->is(
                                $locale . '/' . $messages),
                        ]) />
                @endif
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>

        <x-slot:footer>
            <x-footer />
        </x-slot>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />

    @cookieconsentview

    @cookieconsentbutton(
        action: 'reset',
        label: 'Cookies', //'Manage cookies'
        attributes: [
            'id' => 'reset-button',
            'class' => 'fixed bottom-5 right-5', //'btn'
        ]
    )

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.1/Sortable.min.js"></script>
</body>

</html>
