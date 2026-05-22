<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    public Collection $senders;

    public Collection $couriers;

    public Language $language;

    public function mount(): void
    {
        $user = Auth::user();

        $user->load(['senders.translations', 'couriers.translations']);

        $this->senders = $user->senders;

        $this->couriers = $user->couriers;

        $this->language = Language::firstWhere('code', App::currentLocale());
    }

    public function deleteSender(int $id): void
    {
        $sender = $this->senders->firstWhere('id', $id);

        abort_unless($sender, 404);

        $this->authorize('delete', $sender);

        $sender->delete();

        $this->senders = $this->senders->reject(fn($item) => $item->id === $id)->values();
    }

    public function deleteCourier(int $id): void
    {
        $courier = $this->couriers->firstWhere('id', $id);

        abort_unless($courier, 404);

        $this->authorize('delete', $courier);

        $courier->delete();

        $this->couriers = $this->couriers->reject(fn($item) => $item->id === $id)->values();
    }
}; ?>

<div>
    @if ($senders->isNotEmpty() || $couriers->isNotEmpty())
        <div class="mb-5">
            <x-header subtitle="{{ __('This is a list of all your ads. You can delete them here.') }}" separator>
                <x-slot:title class="!text-xl">
                    {{ __('My announcements') }}
                </x-slot>
            </x-header>

            @if ($senders->isNotEmpty())
                @foreach ($senders as $sender)
                    @php($photo = $sender->firstPhoto())

                    <x-list-item :item="$sender">
                        <x-slot:avatar>
                            <x-avatar :image="$photo" placeholder="{{ $sender->initials() }}"
                                class="!w-10 {{ !$photo ? '!bg-secondary !text-secondary-content' : '' }}" />
                        </x-slot:avatar>

                        <x-slot:value>
                            {{ $sender->translate($language->id)?->thing }}
                        </x-slot:value>

                        @can('update', $sender)
                            <x-slot:actions>
                                <x-button icon="o-pencil" class="btn-circle btn-sm" :tooltip="__('Edit')"
                                    link="{{ route('senders.edit', ['announcement' => $sender]) }}" />

                                <x-button icon="o-trash" class="btn-sm" :tooltip="__('Delete')"
                                    wire:click="deleteSender({{ $sender->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete your ad?') }}" spinner />
                            </x-slot:actions>
                        @endcan
                    </x-list-item>
                @endforeach
            @endif

            @if ($couriers->isNotEmpty())
                @foreach ($couriers as $courier)
                    <x-list-item :item="$courier">
                        <x-slot:avatar>
                            <x-avatar :image="null" placeholder="{{ $courier->initials() }}"
                                class="!w-10 !bg-secondary !text-secondary-content" />
                        </x-slot:avatar>

                        <x-slot:value>
                            {{ $courier->translate($language->id)?->thing }}
                        </x-slot:value>

                        @can('update', $courier)
                            <x-slot:actions>
                                <x-button icon="o-pencil" class="btn-circle btn-sm" :tooltip="__('Edit')"
                                    link="{{ route('couriers.edit', ['announcement' => $courier]) }}" />

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
