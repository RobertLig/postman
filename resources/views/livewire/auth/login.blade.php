<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\RateLimiter;

new #[Title('Login')]
class extends Component {
    use Toast;

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
        $executed = RateLimiter::attempt(
            'login:',
            $perMinute = 5,
            function() {
                //validation
                $credentials = $this->validate();

                //authentication
                if (!Auth::attempt($credentials, $this->remember)) {
                    throw ValidationException::withMessages([
                        'email' => __('auth.failed')
                    ]);
                }

                Session::regenerate();

                //get user's timezone from his ip address 
                /*$ipInfo = Http::get('http://ip-api.com/json/' . request()->ip()); 

                $timezone = $ipInfo->json()['timezone'] ?? 'Europe/London'; 

                //dd($timezone);

                $user = Auth::user(); 

                $user->update(['timezone' => $timezone]); */ //cannot update user. Why?

                $this->success(
                    __('Logged in successfully!'), 
                    position: 'toast-bottom',
                    //redirectTo: LaravelLocalization::localizeUrl('/') //doesn't work with redirectIntended
                );

                $this->redirectIntended(LaravelLocalization::localizeUrl('/'));
            }
        );
 
        if (! $executed) {
            $this->error(
                __(
                    'auth.throttle', 
                    ['seconds' => RateLimiter::availableIn('login:')]
                ),
                position: 'toast-bottom',
                timeout: 5000,
            );
        }
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
            <a href="{{ route('password.request') }}" class="link text-sm">{{ __('Forgot your password?') }} </a>
        </div>   

        <x-slot:actions>
            <x-button label="{{ __('Login') }}" icon="o-arrow-right-end-on-rectangle" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>

    <div class="text-end text-sm mt-5">
        {{ __('Don\'t have an account?') }} 
        <a href="{{ route('register') }}" class="link">{{ __('Sign up') }}</a>
    </div>
</div>
