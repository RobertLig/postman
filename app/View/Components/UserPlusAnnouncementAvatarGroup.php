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
        //public ?string $title = null,
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
            <div class="">
                {{-- @if($title)
                    <div @class(["font-semibold font-lg mb-5"]) >
                        {{ $title }}
                    </div>
                @endif --}}
                
                {{-- dd($usersToReceiveMessagesToTheirAnnouncements) --}}

                @foreach ($usersToReceiveMessagesToTheirAnnouncements as $user)
                    @foreach ($user->senderAnnouncements as $senderAnnouncement)
                        <x-list-item :item="$user" value=""
                            link="{{ route('chat', ['user' => $user, 'senderannouncement' => $senderAnnouncement]) }}"> 

                            <x-slot:avatar>
                                <div class="flex -space-x-6">

                                    <x-avatar :image="$senderAnnouncement->firstPhoto()" alt="alt"
                                        placeholder="{{ $senderAnnouncement->initials() }}"
                                        class="!w-10 ring-3 ring-base-100 {{ !$senderAnnouncement->firstPhoto() ? '!bg-secondary !text-secondary-content' : '' }} " />

                                    <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}"
                                        class="!w-10 ring-3 ring-base-100" :badge="$user->countSenderAnnouncementMessages($senderAnnouncement->id)"/>
                                </div>
                            </x-slot:avatar>
                        </x-list-item>
                    @endforeach
                @endforeach

                {{ $usersToReceiveMessagesToTheirAnnouncements->onEachSide(0)->links('vendor.livewire.postman-pagination-messages', ['scrollTo' => false]) }}

            </div>
        blade;
    }
}
