<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="{{ $metaDescription ?? __('Connect package senders with travelers heading the same way. Post delivery or travel announcements and arrange peer-to-peer shipping.') }}">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $metaDescription ?? __('...') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('social-preview.jpg') }}">

    <meta name="twitter:card" content="summary_large_image">

    @stack('styles')

    <title>{{ isset($title) ? __($title) . ' - ' . config('app.name') : config('app.name') }}</title>

    {{-- <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}"> --}}
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}"> --}}

    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- @if (consent('google_ads'))
        <x-analytics.google-ads /> 
    @endif --}}

    @if (request()->routeIs('home') && consent('analytics'))
        <x-analytics.trustpilot-script />
    @endif


</head>

<body class="font-sans antialiased">

    {{-- The navbar with `sticky` and `full-width` --}}
    <x-nav sticky full-width>

        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3" aria-label="Open menu">
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
                class="btn-sm" aria-label="Language selector">

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

            @guest
                <x-button label="{{ __('Register') }}" icon="o-pencil-square" link="{{ route('register') }}" responsive
                    @class([
                        'btn-ghost btn-sm',
                        'bg-neutral border-neutral :hover:bg-neutral text-neutral-content shadow-none' => request()->routeIs(
                            'register'),
                    ]) />
                <x-button label="{{ __('Login') }}" icon="o-arrow-right-end-on-rectangle" link="{{ route('login') }}"
                    responsive @class([
                        'btn-ghost btn-sm',
                        'bg-neutral border-neutral :hover:bg-neutral text-neutral-content shadow-none' => request()->routeIs(
                            'login'),
                    ]) />
            @endguest

            @auth
                <livewire:settings.dropdown-login />
            @endauth
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
                    @class([
                        'bg-neutral text-neutral-content' => request()->routeIs('home'),
                    ]) />

                <x-menu-item title="{{ __('Senders` announcements') }}" icon="o-clipboard-document-list"
                    link="{{ route('senders') }}" @class([
                        'bg-neutral text-neutral-content' => request()->routeIs('senders*'),
                    ]) />

                <x-menu-item title="{{ __('Courier`s announcements') }}" icon="o-clipboard-document-list"
                    link="{{ route('couriers') }}" @class([
                        'bg-neutral text-neutral-content' => request()->routeIs('couriers*'),
                    ]) />

                @auth
                    <livewire:conversations.unread-messages-count />
                @endauth
            </x-menu>
        </x-slot:sidebar>

        <x-slot:content>
            {{ $slot }}
        </x-slot:content>

        <x-slot:footer>
            <x-footer />
        </x-slot>
    </x-main>

    <x-consent.banner />

    <x-toast />

    @stack('scripts')

</body>

</html>
