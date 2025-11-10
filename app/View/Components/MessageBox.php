<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MessageBox extends Component
{
    public string $uuid;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $id = null,
    )
    {
        $this->uuid = "robert" . md5(serialize($this)) . $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div wire:key="{{ $uuid }}" {{-- doesn't work for joining and leaving x-data="{ onlineUsers: [] }" x-init="
                        window.Echo.join('chatroom')
                            .here((users) => {
                                console.log(users);
                            })
                            .joining((user) => {
                                console.log(user);
                            })
                            .leaving((user) => {
                                console.log(user);
                            })
                            .listen('UserEnterAnnouncement', (event) => {
                                console.log(event);
                            }); 
            " --}}>
                <div class="flex flex-wrap gap-5">
                    <livewire:sender-announcement-presence /> 

                    <livewire:show-users-that-sent-message-to-sender-announcement />

                    <livewire:show-users-to-receive-messages-to-their-announcements />

                    <livewire:courier-presence />

                    <livewire:show-all-users />
                </div>

                {{-- doesn't work for joining and leaving 
                <script>
                    document.addEventListener('livewire:initialized', () => {
                        window.Echo.join('chatroom')
                            .here((users) => {
                                console.log(users);
                            })
                            .joining((user) => {
                                console.log(user);
                            })
                            .leaving((user) => {
                                console.log(user);
                            })
                            .listen('UserEnterAnnouncement', (event) => {
                                console.log(event);
                            });
                    });
                </script> --}}
            </div>
        blade;
    }
}
