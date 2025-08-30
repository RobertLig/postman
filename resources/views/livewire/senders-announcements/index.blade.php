<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use App\Models\SenderAnnouncement;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;

new #[Title('Senders` announcements')]
class extends Component {
    //public SenderAnnouncement $senderAnnouncement;

    public $senderAnnouncements;

    public $language;

    //public $photo;

    public function mount() //SenderAnnouncement $senderAnnouncement
    {
        //$this->senderAnnouncement = $senderAnnouncement;

        $this->senderAnnouncements = SenderAnnouncement::all();

        $this->language = Language::where('code', App::currentLocale())->first();

        //$this->photo = Storage::url('avatars/'.$user->avatar);

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

    <div class="grid sm:grid-cols-2 gap-5"> {{--  --}}
        {{-- {{ dd($senderAnnouncements) }} --}}

        @foreach ($senderAnnouncements as $senderAnnouncement)
        {{-- $title = $senderAnnouncement->translate(App::currentLocale()); --}}
        <x-card :title="$senderAnnouncement->translate($language->id)->thing" shadow separator :key="$senderAnnouncement->id" >
            <div class="flex items-center justify-between gap-3">
                <x-badge :value="__('From')" class="badge-soft" />
                <div>{!! Str::limit($senderAnnouncement->translate($language->id)->posting_place, 30) !!}</div>
            </div>

            <div class="flex items-center justify-between gap-3 mt-2">
                <x-badge :value="__('on')" class="badge-soft" />
                <div>{!! Str::limit($senderAnnouncement->posting_day.' '.$senderAnnouncement->translate($language->id)->posting_month.' '.$senderAnnouncement->posting_year.' '.$senderAnnouncement->posting_hour.':'.($senderAnnouncement->posting_minute < 10 ? '0'.$senderAnnouncement->posting_minute : $senderAnnouncement->posting_minute), 30) !!}</div>
            </div>

            <div class="flex items-center justify-between gap-3 mt-2">
                <x-badge :value="__('To')" class="badge-soft" />
                <div>{!! Str::limit($senderAnnouncement->translate($language->id)->reception_place, 30) !!}</div>
            </div>

            <div class="flex items-center justify-between gap-3 mt-2">
                <x-badge :value="__('on')" class="badge-soft" />
                <div>{!! Str::limit($senderAnnouncement->reception_day.' '.$senderAnnouncement->translate($language->id)->reception_month.' '.$senderAnnouncement->reception_year.' '.$senderAnnouncement->reception_hour.':'.($senderAnnouncement->reception_minute < 10 ? '0'.$senderAnnouncement->reception_minute : $senderAnnouncement->reception_minute), 30) !!}</div>
            </div>
 
            <x-slot:figure>
                <img src="{{ $senderAnnouncement->photo_url_1 ? Storage::url('senders-announcements/'.$senderAnnouncement->photo_url_1) : Storage::url('senders-announcements/no-photo.jpg') }}" class="w-[500px] h-[200px] object-cover"/> {{-- https://picsum.photos/500/200  --}}
            </x-slot:figure>

            <x-slot:menu>
                <x-button icon="o-pencil" class="btn-circle btn-sm" :tooltip="__('Edit')" link="{{ route('senders-announcements.edit', ['slug' => $senderAnnouncement]) }}" />
                <x-icon name="o-trash" class="cursor-pointer" />
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
