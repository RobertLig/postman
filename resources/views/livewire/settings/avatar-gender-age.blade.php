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

    #[Validate('nullable|in:male,female')]
    public $gender;

    #[Validate('nullable|in:male,female')]
    public $genderPrev;

    public function mount()
    {
        $user = Auth::user();

        if($user->avatar)
        {
            $this->avatar = Storage::url('avatars/'.$user->avatar);

            $this->photo = true; //to show trash bin
        }

        //$this->avatar = $user->avatar ? Storage::url('avatars/'.$user->avatar) : null;

        $this->gender = $user->gender;

        $this->genderPrev = $user->gender;
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

    public function updateGender()
    {
        //$this->validate(); //not needed for radio button?
        //dd($this->gender);

        $user = Auth::user();

        $user->update(['gender' => $this->gender]);
    }

    public function resetGender($value)
    {
        //dd($value);

        if($this->genderPrev === $this->gender )
        {
            $this->gender = null;

            $this->genderPrev = null;
        }
        else 
        {
            $this->genderPrev = $this->gender;
        }
    }

    public function updateProfile()
    {
        $this->updatePhoto();
        
        $this->updateGender();
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if(!$user->avatar) //Storage::exists('upload/test.png')
        {
            return;
        }

        //$this->photo = null;

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
                @if($photo)  {{--  --}}
                    <x-button x-on:click="$wire.set('photo', null); $wire.deletePhoto(); document.querySelector('div[x-ref] img').src = '{{ Storage::url('avatars/empty-user.jpg') }}';" 
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
 
            <x-radio label="{{ __('Gender') }}" wire:model="gender" wire:click="resetGender($event.target.value)" :options="$users" />
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
