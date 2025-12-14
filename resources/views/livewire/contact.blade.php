<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMailable;
use Illuminate\Support\Facades\RateLimiter;

new #[Title('Contact')]
class extends Component {
    use Toast;

    #[Validate('required|string|max:20')]
    public string $name; //string

    #[Validate('required|email')]
    public string $email;

    #[Validate('required|string|max:200')]
    public string $message;

    public string $metaDescription;

    public function mount()
    {
        $this->metaDescription = __("Contact us if you have any questions.");
    }

    public function save()
    {
        //use Limiter on sending emails, like in reset email
        //...
        
        $this->validate();

        $executed = RateLimiter::attempt(
            'sendMail:',
            $perMinute = 1,
            function() {
                //  this email should be website email
                Mail::to('info@postman.chat')
                    /* ->send((new ContactMailable($this->name, $this->email, $this->message))
                    ->replyTo($this->email, $this->name)
                    ); */ //instead of queue
                    ->queue((new ContactMailable($this->name, $this->email, $this->message))
                        ->replyTo($this->email, $this->name)
                    );
                    
                    //->queue(new ContactMailable($this->name, $this->email, $this->message));

                $this->reset(); 

                $this->success(
                    __('Your message has been sent successfully!'), 
                    position: 'toast-bottom'
                );
            }
        );
        
        if (! $executed) {
            $this->error(
                __(
                    'email.throttle', 
                    ['seconds' => RateLimiter::availableIn('sendMail:')]
                ),
                position: 'toast-bottom',
                timeout: 5000,
            );
        }
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
