<div>
    <div class="flex flex-wrap gap-5">
        @foreach ($senderAnnouncements as $senderAnnouncement)

            <div>
                <x-list-item :item="$senderAnnouncement"
                    link="{{ route('senders-announcements.show', ['senderannouncement' => $senderAnnouncement]) }}">
                    <x-slot:avatar>
                        <x-popover position="top-start">
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
                    @foreach ($presentUsersTransformedAndPaginated as $presentUser)
                        @if ($presentUser[0] == $senderAnnouncement->id)
                            <x-list-item :item="$presentUser[1]"
                                link="{{ route('chat', ['user' => $presentUser[1], 'senderannouncement' => $senderAnnouncement]) }}">
                                {{-- --}}
                                <x-slot:avatar>
                                    <x-avatar :image="$presentUser[1]->getAvatar()" alt="alt"
                                        placeholder="{{ $presentUser[1]->initials() }}" class="!w-10" />
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
