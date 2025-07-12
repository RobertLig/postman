<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;

new #[Title('Reset password')]
class extends Component {
    #[Locked]
    #[Validate('required')]
    public $token = '';

    #[Validate('required|email|unique:users')]
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

    public function mount(string $token)
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    public function resetPassword()
    {
        $this->validate();

        
    }
}; ?>

<div>
    <x-header title="{{ __('Reset password') }}" subtitle="{{ __('Enter your new password.') }}" separator />

    <x-form wire:submit="resetPassword">
        <x-password label="{{ __('Password') }}" wire:model="password" placeholder="{{ __('Password') }}"  clearable />

        <x-password label="{{ __('Password confirmation') }}" wire:model="password_confirmation" placeholder="{{ __('Password confirmation') }}" clearable />
  
        <x-slot:actions>
            <x-button label="{{ __('Reset password') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="resetPassword" />
        </x-slot:actions>
    </x-form>
</div>
