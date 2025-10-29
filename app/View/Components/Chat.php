<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\SenderAnnouncement;

class Chat extends Component
{
    public $senderAnnouncement;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public object|array $chatMessages,
        public object|array $selectedUser,
        public ?string $authUserAvatar,
        public ?string $selectedUserAvatar,
        public ?string $timezone,
        public ?string $subtitle,
        public ?string $senderAnnouncementID,
        public ?string $presenceIndicator = null // | public bool $presenceIndicator
    )
    {
        //dd($this->senderAnnouncementID);

        $this->senderAnnouncement = SenderAnnouncement::find($this->senderAnnouncementID);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
        <div>
            <x-header title="{{ __('Send a message to ') }} {{ $selectedUser->name }}" subtitle="{{ __($subtitle) }}"  >
                    
                <x-slot:actions> {{-- $selectedUserAvatar --}}
                    {{-- <x-avatar :image="$selectedUser->getAvatar()" 
                        placeholder="{{ $selectedUser->initials() }}" class="!w-10" /> --}}

                    <div class="avatar-group -space-x-6">
                        <div class="avatar">
                            <div class="w-12">
                            <img src="https://img.daisyui.com/images/profile/demo/batperson@192.webp" />
                            </div>
                        </div>
                        <div class="avatar">
                            <div class="w-12">
                            <img src="https://img.daisyui.com/images/profile/demo/spiderperson@192.webp" />
                        </div>
                    </div>

                    <x-avatar-with-indicator :image="$selectedUser->getAvatar()" alt="alt" 
                        placeholder="{{ $selectedUser->initials() }}" class="!w-10" 
                        :presenceIndicator="$presenceIndicator" /> {{-- :indicator="$indicator" --}}

                </x-slot:actions>

            </x-header>

            <div class="mt-10 max-w-xl"
                {{-- cannot whisper from x-data, only from <script> below. Why? --}}

                {{-- x-data="{
                    handleUserTypingEvent(event)
                    {
                        console.log(event);

                        window.Echo.private(`chat.${event.selectedUserID}`).whisper('typing', {
                            userID: event.userID,
                            userName: event.userName
                        }); 
                    }
                }"

                x-on:user-typing.camel.window="handleUserTypingEvent" 

                x-init="
                    window.Echo.private(`chat.{{ auth()->user()->id }}`).listenForWhisper('typing', (event) => {
                        let typingIndicator = document.getElementById('typing-indicator');

                        typingIndicator.innerHTML = `${event.userName} {{ __('is typing') }} ` + `<span class='loading loading-dots loading-xs'></span>`;

                        setTimeout(() => {
                            typingIndicator.innerHTML = '';
                        }, 2000);  

                        console.log('tata');
                    });
                " --}}
            >
                {{-- <div class="text-xl font-medium ">{{ __('Send a message') }}</div>

                <div class="text-base-content/50 text-sm mt-1 mb-5">
                    {{ __('You can agree on the details of the ad.') }}
                </div> --}}

                @foreach($chatMessages as $message)

                @if ($loop->first)
                    <div class="divider">{{ $message->created_at->setTimezone( $timezone )->toDateString() }}</div> {{-- $message->created_at->toDateString() --}}
                @elseif ($chatMessages->before($message)->created_at->toDateString() < $message->created_at->toDateString() )
                    <div class="divider">{{ $message->created_at->setTimezone( $timezone )->toDateString() }}</div> {{-- $message->created_at->toDateString() --}}
                @endif

                <div class="chat {{ $message->sender_id === auth()->user()->id ? 'chat-end' : 'chat-start'}} group">

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
                        {{ $message->sender_id === auth()->user()->id ? __('You') : $selectedUser->name }}
                        <time class="text-xs opacity-50">{{ $message->created_at->setTimezone( $timezone )->diffForHumans() }}</time> {{-- $message->created_at->diffForHumans() --}}
                    </div>

                    <div class="flex items-center gap-1">
                        <div @class(["chat-bubble", "bg-accent text-accent-content" => $message->sender_id === auth()->user()->id ])>{{ $message->message }}</div>
 
                        <div class="group-[.chat-end]:order-first">
                            <x-dropdown>
                                <x-slot:trigger>
                                    <x-button icon="o-ellipsis-vertical" class="btn-circle btn-xs" />
                                </x-slot:trigger>
     
                                @can('delete', $message)
                                <x-menu-item title="{{ __('Delete') }}" icon="o-trash" wire:click="deleteMessage({{ $message->id }})" />
                                @endcan 
                            </x-dropdown>   
                        </div>
                    </div>

                    {{-- <div class="chat-footer opacity-50">Delivered</div> --}}
                </div>
                @endforeach

                <div id="typing-indicator" class="text-xs text-base-content/70 h-5 mt-5 "></div>

                <x-form wire:submit="save" no-separator>
                    <x-input label="{{ __('Send a message') }}" wire:model.live="newMessage" placeholder="{{ __('Message') }}" icon="o-chat-bubble-left-right" clearable />

                    <x-slot:actions>
                        <x-button label="{{ __('Send') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
                    </x-slot:actions>
                </x-form> 
            </div>

            <script> 
                document.addEventListener('livewire:initialized', () => {
                    Livewire.on('userTyping', (event) => {
                        console.log(event);

                        /* if(event.senderAnnouncementID)
                        {
                            window.Echo.private(`chat.${event.selectedUserID}.${event.senderAnnouncementID}`).whisper('typing', { 
                                userID: event.userID,
                                userName: event.userName
                            });
                        }
                        else
                        { */
                            window.Echo.private(`chat.${event.selectedUserID}`).whisper('typing', {
                                userID: event.userID,
                                userName: event.userName,
                                senderAnnouncementID: event.senderAnnouncementID
                            });
                        // }
                    });

                    /* window.Echo.private(`chat.{{ auth()->user()->id }}.{{ $senderAnnouncementID }}`).listenForWhisper('typing', (event) => {
                        console.log(`chat.{{ auth()->user()->id }}.{{ $senderAnnouncementID }}`);

                        let typingIndicator = document.getElementById('typing-indicator');

                        typingIndicator.innerHTML = `${event.userName} {{ __('is typing') }} ` + '<span class="loading loading-dots loading-xs"></span>';

                        setTimeout(() => {
                            typingIndicator.innerHTML = '';
                        }, 2000);
                    }); */

                    window.Echo.private(`chat.{{ auth()->user()->id }}`).listenForWhisper('typing', (event) => {

                        //console.log({{ $senderAnnouncementID }}); 

                        if(Number(event.senderAnnouncementID) == {{ (int) $senderAnnouncementID }} ) //doesn't work without casting
                        {
                            let typingIndicator = document.getElementById('typing-indicator');

                            typingIndicator.innerHTML = `${event.userName} {{ __('is typing') }} ` + '<span class="loading loading-dots loading-xs"></span>';

                            setTimeout(() => {
                                typingIndicator.innerHTML = '';
                            }, 2000);
                        }
                    }); 

                    /* window.Echo.join('chatroom')
                        .here((users) => {
                            console.log('Present users: ');
                            console.log(users);
                        })
                        .joining((user) => {
                            console.log('User joined: ');
                            console.log(user);
                        })
                        .leaving((user) => {
                            console.log('User left: ');
                            console.log(user);
                        })
                        .listen('UserEnterAnnouncement', (event) => {
                            console.log(event);
                    }); */
                });
            </script>
        </div>
        blade;
    }
}
