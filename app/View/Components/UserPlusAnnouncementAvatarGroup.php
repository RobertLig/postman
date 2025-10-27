<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserPlusAnnouncementAvatarGroup extends Component
{
    public string $uuid;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public object|array $usersToReceiveMessagesToTheirAnnouncements,
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
            <div>
                @if($title)
                    <div @class(["font-semibold font-lg"]) >
                        {{ $title }}
                    </div>
                @endif
                
                {{-- dd($usersToReceiveMessagesToTheirAnnouncements) --}}

                @foreach ($usersToReceiveMessagesToTheirAnnouncements as $user)
                    @foreach ($user->senderAnnouncements as $senderAnnouncement)
                        <x-list-item :item="$user" value=""
                            link="{{ route('chat', ['user' => $user, 'senderannouncement' => $senderAnnouncement]) }}"> 

                            <x-slot:avatar>
                                <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}"
                                    class="!w-10" :badge="$user->countSenderAnnouncementMessages($senderAnnouncement->id)" />
                            </x-slot:avatar>
                        </x-list-item>
                    @endforeach
                @endforeach

                <div class="avatar-group -space-x-6">
                    <div class="avatar">
                        <div class="w-10">
                            <img src="https://img.daisyui.com/images/profile/demo/batperson@192.webp" />
                        </div>
                    </div>
                    <div class="avatar">
                        <div class="w-10">
                            <img src="https://img.daisyui.com/images/profile/demo/spiderperson@192.webp" />
                        </div>
                    </div>
                </div>
            </div>
        blade;
    }
}
