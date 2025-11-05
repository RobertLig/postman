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
                <x-avatar :image="$senderannouncement->firstPhoto()" alt="alt"
                    placeholder="{{ $senderannouncement->initials() }}"
                    class="!w-10 {{ !$senderannouncement->firstPhoto() ? '!bg-secondary !text-secondary-content' : '' }} " />
            </x-slot:avatar>

            <x-slot:value>
                {{ $senderannouncement->translate($this->language->id)->thing }}
            </x-slot:value>

            @can('update', $senderannouncement) 
            <x-slot:actions>
                <x-button icon="o-pencil" class="btn-circle btn-sm" :tooltip="__('Edit')" link="{{ route('senders-announcements.edit', ['senderannouncement' => $senderannouncement]) }}" />
                <x-button icon="o-trash" class="btn-sm" :tooltip="__('Delete')" wire:click="delete({{ $senderannouncement->id }})" wire:confirm="{{ __('Are you sure you want to delete your ad?') }}" spinner />
            </x-slot:actions>
            @endcan 
        </x-list-item>
    @endforeach
    </div>
    @endif
</div>
