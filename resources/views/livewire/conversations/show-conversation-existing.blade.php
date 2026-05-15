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

                $wire.showTypingIndicator(
                    event.user_name
                );

                clearTimeout(this.timeout);

                this.timeout = setTimeout(() => {
                    $wire.hideTypingIndicator();
                }, 1500);
            });
    },

    typing() {

        Echo.private(
            'conversation.{{ $conversation->id }}'
        ).whisper('typing', {
            user_id: {{ auth()->id() }},
            user_name: '{{ auth()->user()->name }}',
        });
    }
}">

    <x-header title="{{ __('Conversation') }}" subtitle="{{ __('Send messages') }}" separator />

    <div class="space-y-3 mb-5">

        @foreach ($this->messages as $message)
            <div
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
                    {{ $message['created_at'] }}
                </div>

            </div>
        @endforeach

    </div>

    @if ($showTyping)
        <div class="mb-4">

            <div class="chat chat-start">

                <div class="chat-bubble bg-base-200">

                    <div class="flex items-center gap-2">

                        <span class="text-sm">
                            {{ $typingUser }}
                            {{ __('is typing') }}
                        </span>

                        <span class="loading loading-dots loading-sm"></span>

                    </div>

                </div>

            </div>

        </div>
    @endif

    <form wire:submit="send">

        <x-textarea wire:model="body" x-on:input.debounce.300ms="typing()" placeholder="{{ __('Type message...') }}"
            rows="4" />

        <div class="mt-3">

            <x-button :label="__('Send')" icon="o-paper-airplane" type="submit" class="btn-primary" spinner="send" />

        </div>

    </form>

</div>
