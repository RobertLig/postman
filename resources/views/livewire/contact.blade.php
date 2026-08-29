<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\ContactMailable;

new #[Title('Contact')] class extends Component {
    use Toast;

    #[Validate('required|string|min:2|max:50')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|min:10|max:2000')]
    public string $message = '';

    public string $metaDescription;

    // Honeypot trap field (Must remain blank for real humans)
    public string $company_website = '';

    public function mount(): void
    {
        $this->metaDescription = __('Get in touch with us if you have any questions.');
    }

    public function save(): void
    {
        // 1. Honeypot check: Catch bot scripts instantly
        if (!empty($this->company_website)) {
            $this->reset();
            return;
        }

        $this->validate();

        $key = 'contact-form:' . request()->ip();

        $executed = RateLimiter::attempt($key, 1, function () {
            Mail::to('info@postman.chat')->queue(new ContactMailable($this->name, $this->email, $this->message));

            $this->reset();

            $this->success(__('Your message has been sent successfully.'), position: 'toast-bottom');
        });

        if (!$executed) {
            $this->error(
                __('email.throttle', [
                    'seconds' => RateLimiter::availableIn($key),
                ]),
                position: 'toast-bottom',
                timeout: 5000,
            );
        }
    }
}; ?>

<div>

    <x-header title="{{ __('Contact form') }}" subtitle="{{ __('Get in touch with us if you have any questions.') }}"
        separator />

    <x-form wire:submit="save">

        {{-- Honeypot Input: Hidden from real users, enticing to bots --}}
        <div class="hidden" style="display:none !important;" aria-hidden="true">
            <input type="text" wire:model="company_website" tabindex="-1" autocomplete="off"
                placeholder="Company site URL...">
        </div>

        <x-input label="{{ __('Your full name') }}" wire:model="name" placeholder="{{ __('Your full name') }}"
            icon="o-user" clearable />

        <x-input label="{{ __('Your E-Mail Address') }}" wire:model="email" placeholder="{{ __('mail@site.com') }}"
            icon="o-envelope" clearable />

        <x-textarea label="{{ __('Message') }}" wire:model="message"
            placeholder="{{ __('Write your message here...') }}" hint="{{ __('Max 2000 characters') }}"
            rows="6" />

        <x-slot:actions>

            <x-button label="{{ __('Send message') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="save" />

        </x-slot:actions>

    </x-form>

</div>
