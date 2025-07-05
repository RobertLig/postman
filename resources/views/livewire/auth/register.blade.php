<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

new #[Title('Register')]
class extends Component {
    #[Validate('required')] 
    public $name = '';
 
    #[Validate('required|email')]
    public $email = '';

    #[Validate]
    public $password = '';

    #[Validate('required|same:password')]
    public $password_confirmation = '';

    protected function rules() 
    {
        return [
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ];
    }

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)  
        ]);
 
        return redirect()->to( LaravelLocalization::localizeUrl('/') );
    }
}; ?>

<div>
    <x-header title="{{ __('Register') }}" separator />

    <x-form wire:submit="save">
        <x-input label="{{ __('Name') }}" wire:model="name" placeholder="{{ __('Your name') }}" icon="o-user" hint="{{ __('Your full name') }}" clearable />
 
        <x-input label="{{ __('E-Mail Address') }}" wire:model="email" placeholder="{{ __('mail@site.com') }}" icon="o-envelope"  clearable />

        <x-password label="{{ __('Password') }}" wire:model="password" placeholder="{{ __('Password') }}"  clearable />

        <x-password label="{{ __('Password confirmation') }}" wire:model="password_confirmation" placeholder="{{ __('Password confirmation') }}" clearable />
  
        <x-slot:actions>
            <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
