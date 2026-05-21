<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Auth; //?

new #[Title('Reset password')] class extends Component {
    use Toast;

    #[Locked]
    #[Validate('required')]
    public $token = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate]
    public $password = '';

    #[Validate('required')]
    public $password_confirmation = '';

    protected function rules()
    {
        return [
            'password' => ['required', Rules\Password::min(8)->letters()->numbers(), 'confirmed'],
        ];
    }

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = strtolower(trim(request()->string('email')));
    }

    public function resetPassword(): void
    {
        $credentials = $this->validate();

        $status = Password::reset($credentials, function (User $user, string $password) {
            $user
                ->forceFill([
                    'password' => Hash::make($password),
                ])
                ->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));

            Auth::logoutOtherDevices($password); //?
        });

        $status === Password::PasswordReset ? $this->success(__($status), position: 'toast-bottom', redirectTo: route('login')) : $this->error(__($status), position: 'toast-bottom', timeout: 5000);
    }
}; ?>

<div>
    <x-header title="{{ __('Reset password') }}" subtitle="{{ __('Enter your new password.') }}" separator />

    <x-form wire:submit="resetPassword">
        <x-password label="{{ __('Password') }}" wire:model="password" placeholder="{{ __('Password') }}" clearable />

        <x-password label="{{ __('Password confirmation') }}" wire:model="password_confirmation"
            placeholder="{{ __('Password confirmation') }}" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Reset password') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="resetPassword" />
        </x-slot:actions>
    </x-form>
</div>
