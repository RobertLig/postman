<div x-data="{
    timeout: null,

    init() {

        Echo.private(
                'conversation.{{ $conversation->id }}'
            )

            .listenForWhisper('typing', (event) => {

                if (event.user_id === {{ auth()->id() }}) {
                    return;
                }

                if ($wire.__instance) {

                    $wire.showTypingIndicator(
                        event.user_name
                    );

                    clearTimeout(this.timeout);

                    this.timeout = setTimeout(() => {

                        if ($wire.__instance) {
                            $wire.hideTypingIndicator();
                        }

                    }, 1500);
                }
            });
    },

    typing() {

        Echo.private(
            'conversation.{{ $conversation->id }}'
        ).whisper('typing', {
            user_id: {{ auth()->id() }},
            user_name: '{{ auth()->user()->name }}',
        });
    },

    cleanup() {

        Echo.leave(
            'private-conversation.{{ $conversation->id }}'
        );
    }
}" x-init="init();

window.addEventListener('beforeunload', cleanup);">

    <x-header title="{{ __('Conversation') }}" subtitle="{{ __('Send messages') }}" separator />

    <div class="flex items-center justify-between mb-5">

        <div>

            <div class="text-lg font-semibold">
                {{ $otherUser->name }}
            </div>

            <div class="text-sm opacity-70">
                {{ __('Conversation partner') }}
            </div>

        </div>

        @if (!auth()->user()->hasBlocked($otherUser))
            <x-button icon="o-no-symbol" :label="__('Block user')" wire:click="blockUser"
                wire:confirm="{{ __('Block this user?') }}" class="btn-error btn-sm" />
        @else
            <x-badge value="{{ __('Blocked') }}" class="badge-error" />
        @endif

    </div>

    @if (auth()->user()->hasBlocked($otherUser) || $otherUser->hasBlocked(auth()->user()))
        <x-alert icon="o-no-symbol" class="alert-error mb-4">
            {{ __('Messaging is unavailable.') }}
        </x-alert>
    @endif

    <div class="space-y-3 mb-5">

        @foreach ($this->messages as $message)
            <div wire:key="message-{{ $message['id'] }}"
                class="
                    chat
                    {{ $message['user_id'] === auth()->id() ? 'chat-end' : 'chat-start' }}
                ">

                <div class="chat-header mb-1">
                    {{ $message['user_name'] }}
                </div>

                <div
                    class="
                        chat-bubble
                        {{ $message['user_id'] === auth()->id() ? 'chat-bubble-primary' : '' }}
                    ">
                    {{ $message['body'] }}
                </div>

                <div class="chat-footer opacity-50 text-xs mt-1">

                    <div class="flex items-center gap-2">

                        <span>
                            {{ $message['created_at'] }}
                        </span>

                        <x-button icon="o-trash" wire:click="deleteMessage({{ $message['id'] }})"
                            wire:confirm="{{ __('Delete message?') }}" class="btn-ghost btn-xs text-error" />

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    <div class="h-14 mb-2">

        <div
            class="
            transition-opacity
            duration-200
            {{ $showTyping ? 'opacity-100' : 'opacity-0' }}
        ">

            <div class="chat chat-start">

                <div class="chat-bubble bg-base-200">

                    <div class="flex items-center gap-2">

                        <span class="text-sm">

                            {{ $typingUser ?: __('Someone') }}
                            {{ __('is typing') }}

                        </span>

                        <span class="loading loading-dots loading-sm"></span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @if (!auth()->user()->hasBlocked($otherUser) && !$otherUser->hasBlocked(auth()->user()))
        <form wire:submit="send">

            <x-textarea wire:model="body" x-on:input.debounce.300ms="typing()"
                placeholder="{{ __('Type message...') }}" rows="4" />

            <div class="mt-3">

                <x-button :label="__('Send')" icon="o-paper-airplane" type="submit" class="btn-primary" spinner="send" />

            </div>

        </form>
    @endif

</div>
