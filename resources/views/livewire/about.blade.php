<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

new #[Title('About us')]
class extends Component {
    public string $metaDescription;

    public function mount()
    {
        $this->metaDescription = Str::limit(strip_tags(__("This service aims to connect people who want to send a package with those who are traveling there. You can post ads in two categories: as a sender or as a courier . As a sender, which means someone who wants to to send something somewhere and is looking for a courier. As a courier, which means someone who is traveling somewhere and can deliver something to someone there.")), 150);
    }
}; ?>

<div>
    <x-header title="{{ __('About the website') }}" subtitle="{{ __('What is this service about?') }}" separator />

    {{-- hero daisy component --}}
    <div class="hero  min-h-screen"> {{-- bg-base-200 --}}
        <div class="hero-content text-center">
            <div class="max-w-md">
                <h1 class="text-5xl font-bold">{{ __("Hello there") }}</h1>
                <p class="py-6">
                    {{ __("This service aims to connect people who want to send a package with those who are traveling there. You can post ads in two categories: as a ") }}
                    <a class="link font-medium" href="{{ route('senders-announcements.create') }}">{{ __("sender") }}</a> 
                    {{-- <x-button label="{{ __('sender') }}" link="{{ route('senders-announcements.create') }}" class="btn-xs" /> --}}
                    {{ __(" or as a ") }}
                    <a
                        class="link font-medium"
                        href="{{ route('couriers-announcements.create') }}"
                        onclick="if (typeof gtag === 'function') { gtag('event', 'conversion', { 'send_to': 'AW-17861754370/P4SpCL6Ms-AbEIL8ksVC' }); }"
                        >
                        {{ __('courier') }}
                    </a>
                    {{-- <a class="link font-medium" href="{{ route('couriers-announcements.create') }}">{{ __("courier") }}</a> --}}
                    {{-- <x-button label="{{ __('courier') }}" link="{{ route('couriers-announcements.create') }}" class="btn-xs" /> --}}
                    {{ __(". As a sender, which means someone who wants to to send something somewhere and is looking for a courier. As a courier, which means someone who's traveling somewhere and can deliver something to someone there.") }}
                </p>
            </div>
        </div>
    </div>

    {{-- "This service aims to connect people who want to send a package with those who are traveling there. You can post ads in two categories: as a sender or as a courier. As a sender, which means someone who wants to to send something somewhere and is looking for a courier. As a courier, which means someone who's traveling somewhere and can deliver something to someone there.": 
         "Serwis ten ma na celu skojarzyć ludzi którzy chcą gdzieś nadać przesyłkę z tymi, którzy tam jadą. Można wystawiać ogłoszenia w dwóch kategoriach: jako nadawca lub jako kurier. Jako nadawca, czyli ktoś kto chce coś gdzieś wysłać i szuka kuriera. Jako kurier, czyli ktoś kto gdzieś jedzie i może komuś coś tam dostarczyć.", --}}
</div>
