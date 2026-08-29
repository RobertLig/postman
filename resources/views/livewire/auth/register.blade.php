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
use Illuminate\Support\Facades\RateLimiter;

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

    // Honeypot trap field (Must remain blank for real humans)
    public string $my_website_url = '';

    protected function rules()
    {
        return [
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ];
    }

    public function save()
    {
        // 1. Honeypot check: If the hidden field is filled out, it's a bot!
        if (!empty($this->my_website_url)) {
            // Silently fail to confuse the bot script into thinking it succeeded
            $this->reset();
            return;
        }

        $this->validate();

        // 2. Rate Limiting protection
        $key = 'registration-form:' . request()->ip();

        $executed = RateLimiter::attempt(
            $key,
            3,
            function () {
                $user = User::create([
                    'name' => trim($this->name),
                    'email' => strtolower(trim($this->email)),
                    'password' => Hash::make($this->password),
                ]);

                event(new Registered($user));

                Auth::login($user);

                $this->success(__('Registered successfully!'), position: 'toast-bottom', redirectTo: route('verification.notice'));
            },
            3600,
        ); // Max 3 registrations per hour per IP

        if (!$executed) {
            $this->error(
                __('Too many registration attempts. Please try again in :seconds seconds.', [
                    'seconds' => RateLimiter::availableIn($key),
                ]),
                position: 'toast-bottom',
                timeout: 5000,
            );
        }
    }
}; ?>

<div>
    <x-header title="{{ __('Register') }}" separator />

    <x-form wire:submit="save">

        {{-- Honeypot Input: Hidden from real users, enticing to bots --}}
        <div class="hidden" style="display:none !important;" aria-hidden="true">
            <input type="text" wire:model="my_website_url" tabindex="-1" autocomplete="off"
                placeholder="Your website here...">
        </div>

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
