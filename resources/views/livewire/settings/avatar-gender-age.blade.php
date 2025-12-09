<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    #[Validate('nullable|image|max:200')] // max:1024
    public $photo;

    #[Validate('nullable|string')]
    public $avatar;

    #[Validate('nullable|in:male,female')]
    public $gender;

    #[Validate('nullable|in:male,female')]
    public $genderPrev;

    #[Validate('nullable|string|in:< 20,20 to 29,30 to 39,40 to 49,50 to 59,60 to 69,70 to 79,80 to 89,> 90')]
    public $age;

    #[Validate('nullable|string|in:< 20,20 to 29,30 to 39,40 to 49,50 to 59,60 to 69,70 to 79,80 to 89,> 90')]
    public $agePrev;

    public function mount()
    {
        $user = Auth::user();

        if($user->avatar)
        {
            $this->avatar = Storage::url('avatars/'.$user->avatar);

            $this->photo = true; //to show trash bin
        }

        $this->gender = $user->gender;

        $this->genderPrev = $user->gender;

        $this->age = $user->age;

        $this->agePrev = $user->age;
    }

    protected function updatePhoto()
    {
        $this->validate(); //not needed for file?

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

    protected function updateGenderAndAge()
    {
        //$this->validate(); //not needed for radio button?
        //dd($this->gender);

        $user = Auth::user();

        $user->update(['gender' => $this->gender, 'age' => $this->age]);
    }

    public function resetGender()
    {
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

    public function resetAge()
    {
        if($this->agePrev === $this->age )
        {
            $this->age = null;

            $this->agePrev = null;
        }
        else 
        {
            $this->agePrev = $this->age;
        }
    }

    public function updateProfile()
    {
        $this->updatePhoto();
        
        $this->updateGenderAndAge();
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
        <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-3xl">
            <!-- avatar -->
            <div>
                <x-file label="{{ __('Photo') }}" wire:model="photo" accept="image/png, image/jpeg" change-text="{{ __('Change') }}"> 
                    <img src="{{ $avatar ?? Storage::url('avatars/empty-user.jpg') }}" class="h-40 rounded-lg" /> {{-- $user->avatar --}}
                </x-file> 
                @if($photo)  {{--  --}}
                    <x-button x-on:click="$wire.set('photo', null); $wire.deletePhoto(); document.querySelector('div[x-ref] img').src = '{{ Storage::url('avatars/empty-user.jpg') }}';" 
                        icon="o-trash" class="btn-circle btn-ghost" tooltip-right="{{ __('Delete photo')}}" /> {{-- wire:click="resetAvatar" --}}
                @endif 

                <x-hr target="deletePhoto" />
            </div>

            <!-- gender -->
            <x-gender />
            
            <!-- age -->
            <x-age />

            {{-- <div>
                <x-range wire:model.live.debounce="age" label="{{ __('Age') }}" /> 

                <span>
                    @if($age != 0)
                        {{ $age }}
                    @endif

                    {{ trans_choice('translations.age', $age) }}
                </span>
            </div> --}}
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
