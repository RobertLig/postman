<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset( $title ) ? __($title).' - '.config('app.name') : config('app.name') }}</title>

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
 
    {{-- Sortable.js --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.1/Sortable.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    @php
        #for active buttons
        $locale =  \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
        $register = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.register');
        $login = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.login');
        $messages = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.messages');
        $about = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.about');
        $sendersAnnouncements = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::transRoute('routes.senders-announcements');
    @endphp
    {{-- The navbar with `sticky` and `full-width` --}}
    <x-nav sticky full-width>
 
        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
 
            {{-- Brand --}}
            {{--<div>Postman</div>--}}
            <x-app-brand /> 
        </x-slot:brand>
 
        {{-- Right side actions --}}
        <x-slot:actions>
            {{-- theme toggle --}}
            <x-theme-toggle darkTheme="synthwave" lightTheme="caramellatte" class="ms-2 sm:ms-0" /> {{--  --}}
            {{-- language selector --}}
            <x-dropdown-select-reload label="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() }}" class="btn-sm">
                <x-slot:icon>
                    <x-icon name="o-chevron-down" class="w-4 h-4" /> 
                    <!-- <x-icons.chevron-down /> -->
                </x-slot> 
                @foreach(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <li>
                        <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            {{ $properties['native'] }}
                        </a>
                    </li>
                @endforeach
            </x-dropdown-select-reload>

            @if(!auth()->user())
                <x-button label="{{ __('Register') }}" icon="o-pencil-square" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/register') }}" 
                    class="btn-ghost btn-sm" responsive 
                    @class(["btn-ghost btn-sm", 
                                   "bg-neutral border-neutral :hover:bg-neutral text-neutral-content shadow-none" => request()->is($locale.'/'.$register)]) /> {{-- $locale.'/register' --}}
                <x-button label="{{ __('Login') }}" icon="o-arrow-right-end-on-rectangle" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/login') }}" 
                    class="btn-ghost btn-sm" responsive 
                    @class(["btn-ghost btn-sm", 
                                   "bg-neutral border-neutral :hover:bg-neutral text-neutral-content shadow-none" => request()->is($locale.'/'.$login)]) />
            @else
                <livewire:settings.dropdown-login /> 
            @endif
        </x-slot:actions> 
    </x-nav>
 
    {{-- The main content with `full-width` --}}
    <x-main with-nav full-width>
 
        {{-- This is a sidebar that works also as a drawer on small screens --}}
        {{-- Notice the `main-drawer` reference here --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200" collapse-text="{{ __('Collapse') }}">
            {{-- Activates the menu item when a route matches the `link` property --}}
            <x-menu> {{-- activate-by-route doesn' work with mcamara localization --}}
                <x-menu-item title="{{ __('Home') }}" icon="o-home" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/') }}" {{--  --}}
                    @class(["bg-neutral text-neutral-content" => request()->is($locale)]) /> {{-- bg-secondary-content --}}
                <x-menu-item title="{{ __('Senders` announcements') }}" icon="o-clipboard-document-list" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/senders-announcements') }}"
                    @class(["bg-neutral text-neutral-content" => request()->is($locale.'/'.$sendersAnnouncements.'*')]) />
                
                @if(auth()->user())
                    <x-menu-item title="{{ __('Messages') }}" icon="o-envelope" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/messages') }}"
                        @class(["bg-neutral text-neutral-content" => request()->is($locale.'/'.$messages)]) />
                @endif

                {{-- <x-menu-item title="{{ __('About us') }}" icon="o-information-circle" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/about') }}"
                    @class(["bg-neutral text-neutral-content" => request()->is($locale.'/'.$about)]) /> --}}
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
</body>
</html>
