<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

new #[Title('Login')]
class extends Component {
    #[Validate('required|email')]
    public $email = '';

    #[Validate]
    public $password = '';

    //#[Validate('accepted')]
    public $remember = false;

    protected function rules() 
    {
        return [
            'password' => ['required', Password::min(8)->letters()->numbers()],
        ];
    }

    public function save()
    {
        $credentials = $this->validate();

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed')
            ]);
        }

        Session::regenerate();
 
        $this->redirectIntended(LaravelLocalization::localizeUrl('/'));
    }
}; ?>

<div>
    <x-header title="{{ __('Login') }}" separator />

    <x-form wire:submit="save">
        <x-input label="{{ __('E-Mail Address') }}" wire:model="email" placeholder="{{ __('mail@site.com') }}" icon="o-envelope"  clearable />

        <x-password label="{{ __('Password') }}" wire:model="password" placeholder="{{ __('Password') }}"  clearable />

        <div class="mt-3 flex items-center justify-between">
            <x-rob-checkbox wire:model="remember">
                <x-slot:label>
                    {{ __('Remember me') }}
                </x-slot>
            </x-rob-checkbox> 
            <a class="link text-sm">{{ __('Forgot your password?') }}</a>
        </div>   

        <x-slot:actions>
            <x-button label="{{ __('Login') }}" icon="o-arrow-right-end-on-rectangle" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
