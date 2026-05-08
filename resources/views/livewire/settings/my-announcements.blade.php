<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Language;
use App\Models\Sender;
use App\Models\Courier;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    public $senders;

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

        $this->senders = $user->senders;

        $this->language = Language::where('code', App::currentLocale())->first();

        $this->couriers = $user->couriers;

        //dd($this->couriers);
    }

    public function delete($id)
    {
        $sender = Sender::find($id);

        $this->authorize('delete', $sender);

        //delete files of the announcement
        foreach ($sender->library as $image) {
            Storage::disk('senders-announcements')->delete($image['path']);
        }

        $sender->delete();

        $this->senders = Auth::user()->senders;
    }

    public function deleteCourier($id)
    {
        //dd($id);

        $courier = Courier::find($id);

        $this->authorize('delete', $courier);

        $courier->delete();

        $this->couriers = Auth::user()->couriers;
    }
}; ?>

<div>
    @if ($senders->count() || $couriers->count())
        <div class="mb-5">
            <x-header subtitle="{{ __('This is a list of all your ads. You can delete them here.') }}" separator>
                <x-slot:title class="!text-xl">
                    {{ __('My announcements') }}
                </x-slot>
            </x-header>

            @if ($senders->count())
                @foreach ($senders as $sender)
                    <x-list-item :item="$sender">
                        <x-slot:avatar>
                            <x-avatar :image="$sender->firstPhoto()" alt="alt" placeholder="{{ $sender->initials() }}"
                                class="!w-10 {{ !$sender->firstPhoto() ? '!bg-secondary !text-secondary-content' : '' }} " />
                        </x-slot:avatar>

                        <x-slot:value>
                            {{ $sender->translate($this->language->id)->thing }}
                        </x-slot:value>

                        @can('update', $sender)
                            <x-slot:actions>
                                <x-button icon="o-pencil" class="btn-circle btn-sm" :tooltip="__('Edit')"
                                    link="{{ route('senders-announcements.edit', ['sender' => $sender]) }}" />
                                <x-button icon="o-trash" class="btn-sm" :tooltip="__('Delete')"
                                    wire:click="delete({{ $sender->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete your ad?') }}" spinner />
                            </x-slot:actions>
                        @endcan
                    </x-list-item>
                @endforeach
            @endif

            @if ($couriers->count())
                @foreach ($couriers as $courier)
                    <x-list-item :item="$courier">
                        <x-slot:avatar>
                            <x-avatar :image="null" alt="alt" placeholder="{{ $courier->initials() }}"
                                class="!w-10 !bg-secondary !text-secondary-content" />
                        </x-slot:avatar>

                        <x-slot:value>
                            {{ $courier->translate($this->language->id)->thing }}
                        </x-slot:value>

                        @can('update', $courier)
                            <x-slot:actions>
                                <x-button icon="o-pencil" class="btn-circle btn-sm" :tooltip="__('Edit')"
                                    link="{{ route('couriers-announcements.edit', ['courier' => $courier]) }}" />
                                <x-button icon="o-trash" class="btn-sm" :tooltip="__('Delete')"
                                    wire:click="deleteCourier({{ $courier->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete your ad?') }}" spinner />
                            </x-slot:actions>
                        @endcan
                    </x-list-item>
                @endforeach
            @endif
        </div>
    @endif
</div>
