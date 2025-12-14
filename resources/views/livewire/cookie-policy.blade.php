<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Whitecube\LaravelCookieConsent\Facades\Cookies;

new #[Title('Cookie policy')]
class extends Component {
    public string $metaDescription;

    public function mount()
    {
        $this->metaDescription = __("Cookie policy");
    }
}; ?>

<div>
    <x-header title="{{ __('Cookie policy') }}" separator />

    {{-- $cookies-> --}}
    {{-- @foreach (Cookies::getCategories() as $category) 
            <h3 class="mt-5 font-medium">{{ $category->title }}</h3>

            @php
                $headers = [
                    ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                    ['key' => 'cookie', 'label' => __('cookieConsent::cookies.cookie'), 'class' => 'w-20'],
                    ['key' => 'purpose', 'label' => __('cookieConsent::cookies.purpose')],
                    ['key' => 'duration', 'label' => __('cookieConsent::cookies.duration'), 'class' => 'w-64', 'sortable' => false],
                ];

                $data = collect();

                foreach ($category->getCookies() as $key => $cookie) {
                    $data->push(['id' => $key+1, 'cookie' => $cookie->name, 'purpose' => $cookie->description, 'duration' => \Carbon\CarbonInterval::minutes($cookie->duration)->cascade()]);
                }
            @endphp

            <x-card shadow>
                <x-table :headers="$headers" :rows="$data" />
            </x-card>
    @endforeach --}}

   @cookieconsentinfo 
</div>
