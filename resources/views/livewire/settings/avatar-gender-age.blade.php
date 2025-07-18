<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $photo;

    public function resetAvatar()
    {
        $this->photo = '';

        //$this->js('onAvatarReset'); 

        $this->dispatch('avatar-reset', url: Storage::url('avatars/empty-user.jpg') ); 
    }
}; ?>

<div>
    <x-header subtitle="{{ __('This may be helpful for others to choose a specific courier.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Photo, gender and age') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updateProfile" no-separator>
        <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-2xl">
            <div>
                <x-file wire:model="photo" accept="image/png, image/jpeg" change-text="{{ __('Change') }}"> 
                    <img src="{{ $user->avatar ?? Storage::url('avatars/empty-user.jpg') }}" class="h-40 rounded-lg" />
                </x-file> 
                {{-- @if($photo != '') --}} {{-- wire:click="resetAvatar" --}}
                    <div x-data="{}">
                    <x-button  x-on:click="$wire.photo = ''; document.querySelector('div[x-ref] img').src = '{{ Storage::url('avatars/empty-user.jpg') }}';" 
                        icon="o-trash" class="btn-circle btn-ghost" />
                    </div>
                {{-- @endif --}}
            </div>
        </div>
  
        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="updateProfile" />
        </x-slot:actions>
    </x-form>

    @script
        <script>
            $wire.on('avatar-reset', (event) => {
                document.querySelector('div[x-ref="preview"] img').src = event.url;

                //console.log(event.url);
            });

            /*$js('onAvatarReset', () => {
                document.querySelector('div[x-ref="preview"] > img').src = 
            })*/
        </script>
    @endscript
</div>
