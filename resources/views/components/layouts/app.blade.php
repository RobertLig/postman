<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

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
            <div>Postman</div>
        </x-slot:brand>
 
        {{-- Right side actions --}}
        <x-slot:actions>
            <details class="dropdown" x-data="{open: false}" @click.outside="open = false" :open="open">
                <summary class="btn btn-sm" @click.prevent="open = !open">
                    {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() }}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </summary>
                <ul class="menu dropdown-content bg-base-100 rounded-box z-1 p-2 shadow-sm" @click="open = false">
                    @foreach(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                {{ $properties['native'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </details>
            <x-button label="{{ __('Register') }}" icon="o-envelope" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/register') }}" 
                class="btn-ghost btn-sm" responsive 
                @class(["btn-ghost btn-sm", 
                               "bg-secondary-content border-secondary-content :hover:bg-secondary-content shadow-none" => request()->is($locale.'/'.$register)]) /> {{-- $locale.'/register' --}}
            <x-button label="{{ __('Login') }}" icon="o-bell" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/login') }}" 
                class="btn-ghost btn-sm" responsive 
                @class(["btn-ghost btn-sm", 
                               "bg-secondary-content border-secondary-content :hover:bg-secondary-content shadow-none" => request()->is($locale.'/'.$login)]) />
        </x-slot:actions> 
    </x-nav>
 
    {{-- The main content with `full-width` --}}
    <x-main with-nav full-width>
 
        {{-- This is a sidebar that works also as a drawer on small screens --}}
        {{-- Notice the `main-drawer` reference here --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">
 
            {{-- User --}}
            @if($user = auth()->user())
                <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="pt-2">
                    <x-slot:actions>
                        <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate link="/logout" />
                    </x-slot:actions>
                </x-list-item>
 
                <x-menu-separator />
            @endif
 
            {{-- Activates the menu item when a route matches the `link` property --}}
            <x-menu> {{-- activate-by-route doesn' work with mcamara localization --}}
                <x-menu-item title="{{ __('Home') }}" icon="o-home" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/') }}" {{--  --}}
                    @class(["bg-secondary-content" => request()->is($locale)]) /> 
                <x-menu-item title="{{ __('Senders` announcements') }}" icon="o-home" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/senders-announcements') }}"
                    @class(["bg-secondary-content" => request()->is($locale.'/'.$sendersAnnouncements.'*')]) />
                <x-menu-item title="{{ __('Messages') }}" icon="o-envelope" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/messages') }}"
                    @class(["bg-secondary-content" => request()->is($locale.'/'.$messages)]) />
                <x-menu-sub title="{{ __('Settings') }}" icon="o-cog-6-tooth">
                    <x-menu-item title="Wifi" icon="o-wifi" link="/settings/wifi" />
                    <x-menu-item title="Archives" icon="o-archive-box" link="/settings/archive" /> 
                </x-menu-sub>
                <x-menu-item title="{{ __('About') }}" icon="o-envelope" link="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/about') }}"
                    @class(["bg-secondary-content" => request()->is($locale.'/'.$about)]) /> {{--  --}}
            </x-menu>
        </x-slot:sidebar>
 
        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>
 
    {{--  TOAST area --}}
    <x-toast />
</body>
</html>
