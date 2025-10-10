<div>
    <div class="flex flex-wrap gap-5"> {{-- sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-3xl --}}
        @foreach ($senderAnnouncements as $senderAnnouncement)

            <div>
                <x-popover>
                    <x-slot:trigger>
                        <x-avatar :image="$senderAnnouncement->firstPhoto()" alt="alt" placeholder="{{ $senderAnnouncement->initials() }}" class="!w-10 !bg-secondary !text-secondary-content" />
                    </x-slot:trigger>
                    <x-slot:content>
                        {{ $senderAnnouncement->title() }}
                    </x-slot:content>
                </x-popover>

                <div>{{-- $senderAnnouncementPresence --}}</div>
            </div>

            {{-- {{ $senderAnnouncement->id }} --}}
            
        @endforeach
    
        <div>
            <div>Announcements inbox</div>
            <div></div>
        </div>

        <div>
            <div>All users</div>
            <div></div>
        </div>
        
        <div>
            <div>Sent</div>
            <div></div>
        </div>

    </div>
</div>
