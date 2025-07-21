<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    #[Validate('nullable|image|max:1024')] // 1MB Max
    public $photo;

    #[Validate('nullable|string')]
    public $avatar;

    public function mount()
    {
        $user = Auth::user();

        if($user->avatar)
        {
            $this->avatar = Storage::url('avatars/'.$user->avatar);

            $this->photo = true; //to show trash bin
        }

        //$this->avatar = $user->avatar ? Storage::url('avatars/'.$user->avatar) : null;
    }

    protected function updatePhoto()
    {
        //$this->validate(); not needed for file?

        if($this->photo == null || $this->photo === true) //or !($this->photo == null || $this->photo === true) and put a code in the block
        {
            return;
        }

        $user = Auth::user();

        //upload without deleting the old one
        if($user->avatar) //Storage::exists('upload/test.png')
        {
            Storage::disk('avatars')->delete($user->avatar);
        }

        $path = $this->photo->store(options: 'avatars'); //'avatars', 'public'

        //update database
        $user->update(['avatar' => $path]);

        $this->dispatch('profile-updated');
    }

    public function updateProfile()
    {
        $this->updatePhoto();
        

    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if(!$user->avatar) //Storage::exists('upload/test.png')
        {
            return;
        }

        $this->photo = '';

        //dd(Storage::url('avatars/'.$user->avatar));

        Storage::disk('avatars')->delete($user->avatar);

        $user->update(['avatar' => null]);

        $this->dispatch('profile-updated');
    }

    /* public function resetAvatar()
    {
        $this->photo = '';

        //$this->js('onAvatarReset'); 

        $this->dispatch('avatar-reset', url: Storage::url('avatars/empty-user.jpg') ); 
    } */
}; ?>

<div>
    <x-header subtitle="{{ __('This may be helpful for others to choose a specific courier.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Photo, gender and age') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updateProfile" no-separator>
        <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-2xl">
            <!-- avatar -->
            <div>
                <x-file label="{{ __('Photo') }}" wire:model="photo" accept="image/png, image/jpeg" change-text="{{ __('Change') }}"> 
                    <img src="{{ $avatar ?? Storage::url('avatars/empty-user.jpg') }}" class="h-40 rounded-lg" /> {{-- $user->avatar --}}
                </x-file> 
                @if($photo)  {{-- $wire.set('photo', null); --}}
                    <x-button x-on:click="$wire.deletePhoto(); document.querySelector('div[x-ref] img').src = '{{ Storage::url('avatars/empty-user.jpg') }}';" 
                        icon="o-trash" class="btn-circle btn-ghost" tooltip-right="{{ __('Delete photo')}}" /> {{-- wire:click="resetAvatar" --}}
                @endif 
            </div>

            <!-- gender -->

            @php
                $users = [
                    ['id' => 'male' , 'name' => __('Male')],
                    ['id' => 'female' , 'name' => __('Female')],
                ];
            @endphp
 
            <x-radio label="{{ __('Gender') }}" wire:model="gender" :options="$users" />
        </div>
  
        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="updateProfile" />
        </x-slot:actions>
    </x-form>

    {{-- @script
        <script>
            $wire.on('avatar-reset', (event) => {
                document.querySelector('div[x-ref="preview"] img').src = event.url;

                //console.log(event.url);
            });

            /*$js('onAvatarReset', () => {
                document.querySelector('div[x-ref="preview"] > img').src = 
            })*/
        </script>
    @endscript --}}
</div>
