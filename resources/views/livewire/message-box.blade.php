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
                <x-popover>
                    <x-slot:trigger>
                        <x-avatar :image="$senderAnnouncement->firstPhoto()" alt="alt" placeholder="{{ $senderAnnouncement->initials() }}" class="!w-10 {{ !$senderAnnouncement->firstPhoto() ? '!bg-secondary !text-secondary-content' : '' }} " />
                    </x-slot:trigger>
                    <x-slot:content>
                        {{ $senderAnnouncement->title() }}
                    </x-slot:content>
                </x-popover>

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
                <x-popover>
                    <x-slot:trigger>
                        <x-avatar :image="$senderAnnouncement->firstPhoto()" alt="alt" placeholder="{{ $senderAnnouncement->initials() }}" class="!w-10 {{ !$senderAnnouncement->firstPhoto() ? '!bg-secondary !text-secondary-content' : '' }} " />
                    </x-slot:trigger>
                    <x-slot:content>
                        {{ $senderAnnouncement->title() }}
                    </x-slot:content>
                </x-popover>
                
                <div class="mt-1">
                    {{-- @foreach ($senderAnnouncement->messageSenders() as $user)
                        <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}" class="!w-10" badge="5" />
                    @endforeach --}}

                    @foreach ($users as $user)
                        {{-- @foreach ($user->messages as $message) --}}
                            @if($user->hasSentMessageToThisAnnouncement($senderAnnouncement->id))
                                <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}" class="!w-10" badge="5" /> 
                            @endif
                        {{-- @endforeach --}}
                    @endforeach 
                </div> 
                {{-- $user->countAnnouncementMessages($message->sender_announcement_id) --}}
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
