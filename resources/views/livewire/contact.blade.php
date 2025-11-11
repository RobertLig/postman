<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Mary\Traits\Toast;

new #[Title('Contact')]
class extends Component {
    use Toast;

    #[Validate('required|string|max:20')]
    public string $name;

    #[Validate('required|email')]
    public string $email;

    #[Validate('required|string|max:200')]
    public string $message;

    public function save()
    {
        //use Limiter on sending emails, like in reset email
        //...
        
        $this->validate();

        //sent email

        $this->reset(); 

        $this->success(
            __('Your message has been sent successfully!'), 
            position: 'toast-bottom'
        );
    }
}; ?>

<div>
    <x-header title="{{ __('Contact form') }}" subtitle="{{ __('Contact us if you have any questions.') }}" separator />

    <x-form wire:submit="save">
        
        <x-input label="{{ __('Your full name') }}" wire:model.live="name" placeholder="{{ __('Your full name') }}" icon="o-user"  clearable />

        <x-input label="{{ __('Your E-Mail Address') }}" wire:model.live="email" placeholder="{{ __('mail@site.com') }}" icon="o-envelope"  clearable />

        <x-textarea label="{{ __('Message') }}" wire:model.live="message" placeholder="{{ __('Message') }}" hint="{{ __('Max 200 chars') }}" rows="5" />

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>

    </x-form>
</div>
