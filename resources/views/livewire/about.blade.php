<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

new #[Title('About us')] class extends Component {
    public string $metaDescription;

    public function mount()
    {
        $this->metaDescription = Str::limit(strip_tags(__('Connect with travelers who can deliver your package on their way. Create ads as a sender or courier and arrange deliveries easily.')), 150);
    }
}; ?>

<div>
    <x-header title="{{ __('About the website') }}" subtitle="{{ __('What is this service about?') }}" separator />

    <div class="hero py-20">
        <div class="hero-content text-center">
            <div class="max-w-2xl">

                <h1 class="text-5xl font-bold">
                    {{ __('Welcome') }}
                </h1>

                <p class="py-6 text-base-content/80 leading-8">
                    {{ __('This service connects people who want to send a package with travelers who are already heading to the same destination. You can create ads in two categories: as a ') }}

                    <a class="link font-medium" href="{{ route('senders.create') }}">
                        {{ __('sender') }}
                    </a>

                    {{ __(' or as a ') }}

                    <a class="link font-medium" href="{{ route('couriers.create') }}"
                        onclick="if (typeof gtag === 'function') { gtag('event', 'conversion', { 'send_to': 'AW-17861754370/P4SpCL6Ms-AbEIL8ksVC' }); }">
                        {{ __('courier') }}
                    </a>

                    {{ __('. A sender is someone who wants to ship a package and is looking for a traveler to deliver it. A courier is someone who is already traveling and can bring a package along the way.') }}
                </p>

            </div>
        </div>
    </div>

    <section class="mt-10 max-w-5xl mx-auto px-4 pb-16">

        <h2 class="text-2xl font-semibold mb-4 text-center">
            {{ __('See how the platform works') }}
        </h2>

        <livewire:about-videos />

    </section>
</div>
