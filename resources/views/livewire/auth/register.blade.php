<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mary\Traits\Toast;

new #[Title('Register')] class extends Component {
    use Toast;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|unique:users')]
    public $email = '';

    #[Validate]
    public $password = '';

    #[Validate('required')]
    public $password_confirmation = '';

    #[Validate('accepted')]
    public bool $termsofuse = false;

    protected function rules()
    {
        return [
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => trim($this->name),
            'email' => strtolower(trim($this->email)),
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->success(__('Registered successfully!'), position: 'toast-bottom', redirectTo: route('verification.notice'));
    }
}; ?>

<div>
    <x-header title="{{ __('Register') }}" separator />

    <x-form wire:submit="save">
        <x-input label="{{ __('Name') }}" wire:model="name" placeholder="{{ __('Your name') }}" icon="o-user"
            hint="{{ __('Your full name') }}" clearable autocomplete="name" />

        <x-input label="{{ __('E-Mail Address') }}" wire:model="email" placeholder="{{ __('mail@site.com') }}"
            icon="o-envelope" clearable autocomplete="email" />

        <x-password label="{{ __('Password') }}" wire:model="password" placeholder="{{ __('Password') }}" clearable
            autocomplete="password" />

        <x-password label="{{ __('Password confirmation') }}" wire:model="password_confirmation"
            placeholder="{{ __('Password confirmation') }}" clearable />

        <div class="mt-2">
            <x-rob-checkbox wire:model="termsofuse">
                <x-slot:label>
                    {{ __('I have read the') }} <a href="{{ route('terms-of-use') }}"
                        class="link text-xs">{{ __('terms of use') }} </a>
                </x-slot>
            </x-rob-checkbox>
        </div>

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="save" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-form>

    <div class="text-end text-sm mt-5">{{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="link">{{ __('Log in') }}</a>
    </div>
</div>
