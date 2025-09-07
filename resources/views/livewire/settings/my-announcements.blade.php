<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Language;
use App\Models\SenderAnnouncement;
//use App\Models\User;

new class extends Component {
    public $senderAnnouncements;

    public $language;

    public function mount()
    {
        $user = Auth::user(); 

        $this->senderAnnouncements = $user->senderAnnouncements;

        $this->language = Language::where('code', App::currentLocale())->first();

        //dd($senderAnnouncements);
    }

    public function delete($id)
    {
        $senderannouncement = SenderAnnouncement::find($id);
 
        $this->authorize('delete', $senderannouncement); 

        //delete files of the announcement
        foreach($senderannouncement->library as $image)
        {
            Storage::disk('senders-announcements')->delete($image['path']);
        }
 
        $senderannouncement->delete();

        $this->senderAnnouncements = Auth::user()->senderAnnouncements; 
    }
}; ?>

<div>
    @if($senderAnnouncements->count())
    <div class="mb-5">
    <x-header subtitle="{{ __('This is a list of all your ads. You can delete them here.') }}" separator >
        <x-slot:title class="!text-xl">
            {{ __('My announcements') }}
        </x-slot>
    </x-header>

    @foreach($senderAnnouncements as $senderannouncement)
        <x-list-item :item="$senderannouncement" >
            <x-slot:avatar>
                <div class="py-3">
                    <div class="avatar">
                        <div class="w-11 rounded-full">
                            <img src="{{ $senderannouncement->library->first() ? $senderannouncement->library->first()['url'] : Storage::url('senders-announcements/no-photo.jpg') }}" />
                        </div>
                    </div>
                </div>
            </x-slot:avatar>

            <x-slot:value>
                {{ $senderannouncement->translate($this->language->id)->thing }}
            </x-slot:value>

            <x-slot:actions>
                <x-button icon="o-trash" class="btn-sm" wire:click="delete({{ $senderannouncement->id }})" wire:confirm="{{ __('Are you sure you want to delete your ad?') }}" spinner />
            </x-slot:actions>
        </x-list-item>
    @endforeach
    </div>
    @endif
</div>
