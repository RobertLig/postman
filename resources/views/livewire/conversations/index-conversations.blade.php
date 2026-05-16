<div>

    <x-header title="{{ __('Messages') }}" subtitle="{{ __('Your conversations') }}" separator />

    <div class="space-y-3">

        @forelse ($conversations as $conversation)
            @php

                $otherUser = $conversation->users->firstWhere('id', '!=', auth()->id());

                $latestMessage = $conversation->latestMessage;

            @endphp

            <a href="{{ route('conversations.show.existing', $conversation) }}"
                class="
                    block
                    bg-base-200
                    hover:bg-base-300
                    rounded-xl
                    p-4
                    transition
                ">

                <div class="flex items-center gap-3">

                    <x-avatar :image="$otherUser?->getAvatar()" placeholder="{{ $otherUser?->initials() }}" class="!w-12" />

                    <div class="flex-1 min-w-0">

                        <div class="font-semibold truncate">
                            {{ $otherUser?->name }}
                        </div>

                        <div class="text-sm opacity-70 truncate">

                            {{ $latestMessage?->body }}

                        </div>

                    </div>

                    @if ($latestMessage)
                        <div class="flex flex-col items-end gap-1">

                            <div class="text-xs opacity-50">

                                {{ $latestMessage->created_at->diffForHumans() }}

                            </div>

                            @if ($conversation->unread_count)
                                <x-badge :value="$conversation->unread_count" class="badge-error badge-sm" />
                            @endif

                        </div>
                    @endif

                </div>

            </a>

        @empty

            <x-card>

                <div class="text-center py-10 opacity-70">

                    {{ __('No conversations yet.') }}

                </div>

            </x-card>
        @endforelse

    </div>

</div>
