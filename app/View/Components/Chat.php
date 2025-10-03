<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Chat extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        //public object|array $messages
    )
    {
        //dd($this->messages);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="mt-10 max-w-xl">
                <div class="text-xl font-medium ">{{ __('Send him a message') }}</div>

                <div class="text-base-content/50 text-sm mt-1 mb-5">
                    {{ __('You can agree on the details of the ad.') }}
                </div>

                {{-- @foreach($messages as $message)
                <div class="chat chat-start">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                            <img
                                alt="Tailwind CSS chat bubble component"
                                src="https://img.daisyui.com/images/profile/demo/kenobee@192.webp"
                            />
                        </div>
                    </div>
                    <div class="chat-header">
                        Obi-Wan Kenobi
                        <time class="text-xs opacity-50">12:45</time>
                    </div>
                    <div class="chat-bubble">{{ $message->message }}</div>
                    <div class="chat-footer opacity-50">Delivered</div>
                </div>
                @endforeach --}}

                <div class="chat chat-end">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                            <img
                                alt="Tailwind CSS chat bubble component"
                                src="https://img.daisyui.com/images/profile/demo/anakeen@192.webp"
                            />
                        </div>
                    </div>
                    <div class="chat-header">
                        Anakin
                        <time class="text-xs opacity-50">12:46</time>
                    </div>
                    <div class="chat-bubble">I hate you!</div>
                    <div class="chat-footer opacity-50">Seen at 12:46</div>
                </div> 

                <x-form wire:submit="save" no-separator>
                    <x-input label="{{ __('Send a message') }}" wire:model.live="newMessage" placeholder="{{ __('Message') }}" icon="o-chat-bubble-left-right" clearable />

                    <x-slot:actions>
                        <x-button label="{{ __('Send') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
                    </x-slot:actions>
                </x-form>
            </div>
        blade;
    }
}
