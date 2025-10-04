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
        public object|array $chatMessages,
        public object|array $selectedUser,
        public ?string $authUserAvatar,
        public ?string $selectedUserAvatar
    )
    {
        //dd($this->selectedUserAvatar);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="mt-10 max-w-xl">
                <div class="text-xl font-medium ">{{ __('Send a message') }}</div>

                <div class="text-base-content/50 text-sm mt-1 mb-5">
                    {{ __('You can agree on the details of the ad.') }}
                </div>

                @foreach($chatMessages as $message)
                <div class="chat {{ $message->sender_id === auth()->user()->id ? 'chat-end' : 'chat-start'}} ">

                    <div class="chat-image avatar {{ $message->sender_id === auth()->user()->id ? (empty($authUserAvatar) ? 'avatar-placeholder' : '') : (empty($selectedUserAvatar) ? 'avatar-placeholder' : '') }} ">
                        <div @class(["w-10", "rounded-full", "bg-neutral text-neutral-content" => $message->sender_id === auth()->user()->id ? empty($authUserAvatar) : empty($selectedUserAvatar) ])>
                            @if($message->sender_id === auth()->user()->id ? empty($authUserAvatar) : empty($selectedUserAvatar) ) 
                                <span class="text-xs" alt="alt">{{ $message->sender_id === auth()->user()->id ? auth()->user()->initials() : $selectedUser->initials() }}</span> 
                            @else
                                <img src="{{ $message->sender_id === auth()->user()->id ? $authUserAvatar : $selectedUserAvatar }}" alt="alt"/> 
                            @endif
                        </div>
                    </div> 

                    <div class="chat-header">
                        {{ $message->sender_id === auth()->user()->id ? auth()->user()->name : $selectedUser->name }}
                        <time class="text-xs opacity-50">{{ $message->created_at }}</time>
                    </div>
                    <div @class(["chat-bubble", "bg-accent text-accent-content" => $message->sender_id === auth()->user()->id ])>{{ $message->message }}</div>
                    <div class="chat-footer opacity-50">Delivered</div>
                </div>
                @endforeach

                

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
