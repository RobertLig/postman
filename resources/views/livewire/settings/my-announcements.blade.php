<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Language;
use App\Models\SenderAnnouncement;
use App\Models\Courier;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    public $senderAnnouncements;

    public Collection $couriers;

    public $language;

    public function mount()
    {
        $user = Auth::user(); 

        //doesn't work. Problem with getting models out of this later. How to differentiating for edit, delete, show data?
        /* $saQuery = DB::table('sender_announcements')
            ->select('id', 'library')
            ->where('user_id', $user->id);

        
        $couriersQuery = DB::table('couriers')
            ->select('id', 'posting_day')
            ->where('user_id', $user->id);

        $result = $saQuery->union($couriersQuery)->paginate(1); 

        dd($result); */
        

        $this->senderAnnouncements = $user->senderAnnouncements;

        $this->language = Language::where('code', App::currentLocale())->first();

        $this->couriers = $user->couriers;

        //dd($this->couriers);
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
    @if($senderAnnouncements->count() || $couriers->count())
        <div class="mb-5">
            <x-header subtitle="{{ __('This is a list of all your ads. You can delete them here.') }}" separator >
                <x-slot:title class="!text-xl">
                    {{ __('My announcements') }}
                </x-slot>
            </x-header>

            @if($senderAnnouncements->count())
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
            @endif

            @if($couriers->count())
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
            @endif
        </div>
    @endif
</div>
