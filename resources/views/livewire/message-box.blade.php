<div {{-- doesn't work for joining and leaving x-data="{ onlineUsers: [] }" x-init="
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
    <div class="flex flex-wrap gap-5"> {{-- sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-3xl --}}
        @foreach ($senderAnnouncements as $senderAnnouncement)

            <div>
                <x-list-item :item="$senderAnnouncement" link="{{ route('senders-announcements.show', ['senderannouncement' => $senderAnnouncement]) }}">
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
                    {{--doesn't work @foreach ($presentUsers as $presentUser)
                        <x-avatar :image="$presentUser->getAvatar()" alt="alt" placeholder="{{ $presentUser->initials() }}" class="!w-10" />
                    @endforeach --}}
                </div>

            </div>

            {{-- {{ $senderAnnouncement->id }} --}}
            
        @endforeach
    
        @foreach ($senderAnnouncements as $senderAnnouncement)

            <div>
                <x-list-item :item="$senderAnnouncement" link="{{ route('senders-announcements.show', ['senderannouncement' => $senderAnnouncement]) }}">
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

                <div class="">
                    {{-- @foreach ($senderAnnouncement->messageSenders() as $user)
                        <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}" class="!w-10" badge="5" />
                    @endforeach --}}

                    @foreach ($users as $user)
                        @if($user->hasSentMessageToThisAnnouncement($senderAnnouncement->id))
                            <x-list-item :item="$user" link="{{ route('chat', ['user' => $user]) }}">
                                <x-slot:avatar>
                                    <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}" class="!w-10" :badge="$user->countAnnouncementMessages($senderAnnouncement->id)" />
                                </x-slot:avatar>
                            </x-list-item> 
                        @endif
                    @endforeach 
                </div> 
            </div>

            {{-- {{ $senderAnnouncement->id }} --}}

        @endforeach

        <div>
            <div>All users</div>
            <div></div>
        </div>
        
        <div>
            <div>Sent</div>
            <div></div>
        </div>

    </div>

    {{-- doesn't work for joining and leaving <script>
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
