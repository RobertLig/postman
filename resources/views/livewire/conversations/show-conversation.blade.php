<div>

    <x-header title="{{ __('Conversation') }}" subtitle="{{ __('Send messages') }}" separator />

    <div class="space-y-3 mb-5">

        @foreach ($messages as $message)
            <div
                class="
                    chat
                    {{ $message->user_id === auth()->id() ? 'chat-end' : 'chat-start' }}
                ">

                <div class="chat-header mb-1">
                    {{ $message->user->name }}
                </div>

                <div
                    class="
                        chat-bubble
                        {{ $message->user_id === auth()->id() ? 'chat-bubble-primary' : '' }}
                    ">
                    {{ $message->body }}
                </div>

                <div class="chat-footer opacity-50 text-xs mt-1">
                    {{ $message->created_at->diffForHumans() }}
                </div>

            </div>
        @endforeach

    </div>

    <form wire:submit="send">

        <x-textarea wire:model="body" placeholder="{{ __('Type message...') }}" rows="4" />

        <div class="mt-3">

            <x-button :label="__('Send')" icon="o-paper-airplane" type="submit" class="btn-primary" spinner="send" />

        </div>

    </form>

</div>
