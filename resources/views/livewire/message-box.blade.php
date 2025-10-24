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
        <div>
            <div class="flex flex-wrap gap-5">
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

                            @foreach ($presentUsersTransformedAndPaginated as $presentUser)
                                @if ($presentUser[0] == $senderAnnouncement->id)
                                    <x-list-item :item="$presentUser[1]" link="{{ route('chat', ['user' => $presentUser[1], 'senderannouncement' => $senderAnnouncement]) }}"> {{--  --}}
                                        <x-slot:avatar>
                                            <x-avatar :image="$presentUser[1]->getAvatar()" alt="alt" placeholder="{{ $presentUser[1]->initials() }}" class="!w-10" />
                                        </x-slot:avatar>
                                    </x-list-item>
                                @endif
                            @endforeach
                        </div>

                    </div>

                    {{-- {{ $senderAnnouncement->id }} --}}
                    
                @endforeach 
            </div>

            {{ $presentUsersTransformedAndPaginated->onEachSide(0)->links('vendor.livewire.postman-pagination-messages', ['scrollTo' => false]) }}
        </div>
        
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
                    {{-- @foreach ($senderannouncement->messageSenders() as $user)
                        <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}" class="!w-10" badge="5" />
                    @endforeach --}}
                    
                    @foreach ($users as $user) 
                        @if($user->hasSentMessageToThisAnnouncement($senderAnnouncement->id)) 
                            <x-list-item :item="$user" link="{{ route('chat', ['user' => $user, 'senderannouncement' => $senderAnnouncement]) }}"> {{--  --}}
                                <x-slot:avatar>
                                    <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}" class="!w-10" :badge="$user->countSenderAnnouncementMessages($senderAnnouncement->id)" />
                                </x-slot:avatar>
                            </x-list-item> 
                        @endif
                    @endforeach 
                </div> 
            </div>

            {{-- {{ $senderannouncement->id }} --}}

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
