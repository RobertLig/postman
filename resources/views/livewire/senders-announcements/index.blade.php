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

    <div class="grid sm:grid-cols-2 gap-5"> 
        @foreach ($senderAnnouncements as $senderannouncement)
        {{-- dd($senderannouncement->library) --}} {{-- $senderannouncement->library->first()['url'] --}}
        <x-card :title="$senderannouncement->translate($language->id)->thing" shadow separator :key="$senderannouncement->id" >
            <div class="flex items-center justify-between gap-3">
                <x-badge :value="__('From')" class="badge-soft" />
                <div>{!! Str::limit($senderannouncement->translate($language->id)->posting_place, 30) !!}</div>
            </div>

            <div class="flex items-center justify-between gap-3 mt-2">
                <x-badge :value="__('on')" class="badge-soft" />
                <div>{!! Str::limit($senderannouncement->posting_day.' '.$senderannouncement->translate($language->id)->posting_month.' '.$senderannouncement->posting_year.' '.$senderannouncement->posting_hour.':'.($senderannouncement->posting_minute < 10 ? '0'.$senderannouncement->posting_minute : $senderannouncement->posting_minute), 30) !!}</div>
            </div>

            <div class="flex items-center justify-between gap-3 mt-2">
                <x-badge :value="__('To')" class="badge-soft" />
                <div>{!! Str::limit($senderannouncement->translate($language->id)->reception_place, 30) !!}</div>
            </div>

            <div class="flex items-center justify-between gap-3 mt-2">
                <x-badge :value="__('on')" class="badge-soft" />
                <div>{!! Str::limit($senderannouncement->reception_day.' '.$senderannouncement->translate($language->id)->reception_month.' '.$senderannouncement->reception_year.' '.$senderannouncement->reception_hour.':'.($senderannouncement->reception_minute < 10 ? '0'.$senderannouncement->reception_minute : $senderannouncement->reception_minute), 30) !!}</div>
            </div>

            <x-slot:figure>
                <img src="{{ $senderannouncement->library->first() ? $senderannouncement->library->first()['url'] : Storage::url('senders-announcements/no-photo.jpg') }}" class="w-[500px] h-[200px] object-cover"/> {{-- $senderannouncement->photo_url_1 ? Storage::url('senders-announcements/'.$senderannouncement->photo_url_1) : Storage::url('senders-announcements/no-photo.jpg')  https://picsum.photos/500/200 --}}
            </x-slot:figure>

            @can('update', $senderannouncement) 
            <x-slot:menu>
                <x-button icon="o-pencil" class="btn-circle btn-sm" :tooltip="__('Edit')" link="{{ route('senders-announcements.edit', ['senderannouncement' => $senderannouncement]) }}" /> 
                <x-icon name="o-trash" class="cursor-pointer" />
            </x-slot:menu>
            @endcan 

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
