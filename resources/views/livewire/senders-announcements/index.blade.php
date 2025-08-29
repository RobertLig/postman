<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use App\Models\SenderAnnouncement;
use App\Models\Language;

new #[Title('Senders` announcements')]
class extends Component {
    //public SenderAnnouncement $senderAnnouncement;

    public $senderAnnouncements;

    public $language;

    public function mount() //SenderAnnouncement $senderAnnouncement
    {
        //$this->senderAnnouncement = $senderAnnouncement;

        $this->senderAnnouncements = SenderAnnouncement::all();

        $this->language = Language::where('code', App::currentLocale())->first();

        //dd($this->language->id);
    }
}; ?>

<div>
    <x-header title="{{ __('Senders` announcements') }}" subtitle="{{ __('These are ads from people who would like to send something.') }}" separator >

        <x-slot:actions>
            @if(auth()->user())
                <x-button label="{{ __('Create a new ad') }}" responsive icon="o-plus" link="{{ route('senders-announcements.create') }}" class="btn btn-primary" />
            @endif

            <x-button label="{{ __('Filters') }}" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>

    </x-header>

    <div class="grid sm:grid-cols-2 sm:gap-5"> {{--  --}}
        {{-- {{ dd($senderAnnouncements) }} --}}

        @foreach ($senderAnnouncements as $senderAnnouncement)
        {{-- $title = $senderAnnouncement->translate(App::currentLocale()); --}}
        <x-card :title="$senderAnnouncement->translate($language->id)->thing" shadow separator >
            I am using slots here.
 
            <x-slot:figure>
                <img src="https://picsum.photos/500/200" />
            </x-slot:figure>

            <x-slot:menu>
                <x-button icon="o-share" class="btn-circle btn-sm" />
                <x-icon name="o-heart" class="cursor-pointer" />
            </x-slot:menu>

            <x-slot:actions separator>
                <x-button :label="__('Details')" class="btn-primary" />
            </x-slot:actions>
        </x-card>
        @endforeach 
    </div>

    {{-- @if(auth()->user())
        <x-button label="{{ __('Create a new ad') }}" icon="o-plus" link="{{ route('senders-announcements.create') }}" class="btn btn-primary btn-sm " />
    @endif --}}


</div>
