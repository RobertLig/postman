<div>
    {{-- <div class="font-semibold font-lg mb-5" >
        {{ __('See users who have sent messages to your sender announcements') }}
    </div> --}}

    <div class="flex flex-wrap gap-5">
        @foreach ($senderAnnouncements as $senderAnnouncement)

            <div>
                <x-list-item :item="$senderAnnouncement"
                    link="{{ route('senders-announcements.show', ['senderannouncement' => $senderAnnouncement]) }}">
                    <x-slot:avatar>
                        <x-popover>
                            <x-slot:trigger>
                                <x-avatar :image="$senderAnnouncement->firstPhoto()" alt="alt"
                                    placeholder="{{ $senderAnnouncement->initials() }}"
                                    class="!w-10 {{ !$senderAnnouncement->firstPhoto() ? '!bg-secondary !text-secondary-content' : '' }} " />
                            </x-slot:trigger>
                            <x-slot:content>
                                {{ $senderAnnouncement->title() }}
                            </x-slot:content>
                        </x-popover>
                    </x-slot:avatar>
                </x-list-item>

                <div>
                    @foreach ($users as $user)
                        @if($user->hasSentMessageToThisAnnouncement($senderAnnouncement->id))
                            <x-list-item :item="$user"
                                link="{{ route('chat', ['user' => $user, 'senderannouncement' => $senderAnnouncement]) }}"> {{--
                                --}}
                                <x-slot:avatar>
                                    <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}"
                                        class="!w-10" :badge="$user->countSenderAnnouncementMessages($senderAnnouncement->id)" />
                                </x-slot:avatar>
                            </x-list-item>
                        @endif
                    @endforeach
                </div>
            </div>

        @endforeach
    </div>

    {{-- dd($users) --}}

    {{ $users->onEachSide(0)->links('vendor.livewire.postman-pagination-messages', ['scrollTo' => false]) }}
</div>
