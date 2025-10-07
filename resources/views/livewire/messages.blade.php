<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Events\PublicMessageSent;

new #[Title('Messages')]
    class extends Component {
    #[Validate('nullable|string|max:500')]
    public $newMessage;

    public $chatMessages;

    public $timezone;

    public function mount()
    {
        $this->setMessages();

        //set user timezone in db
        $ipInfo = Http::get('http://ip-api.com/json/' . request()->ip());

        $this->timezone = $ipInfo->json()['timezone'] ?? 'Europe/London'; //'Europe/Warsaw'
    }

    public function setMessages()
    {
        $this->chatMessages = Message::query()
            ->where(function (Builder $query) {
                $query->where('recipient_id', null);
            })
            ->get(); //show all messages without recipient
    }

    public function getListeners()
    {
        return [
            "echo:chat,PublicMessageSent" => 'newPublicMessageNotification'
        ];
    }

    public function newPublicMessageNotification($message)
    { 
        $messageModel = Message::find($message['id']);

        $this->chatMessages->push($messageModel);
    }

    public function save()
    {
        $this->validate();

        if (!$this->newMessage) {
            return;
        }

        $message = Message::create([
            'sender_id' => Auth::user()->id,
            'message' => $this->newMessage,
        ]);

        $this->chatMessages->push($message);

        $this->newMessage = null;

        broadcast(new PublicMessageSent($message))->toOthers(); //uncomment later for public chanel
    }
}; ?>

<div>
    <x-header title="{{ __('Messages') }}" subtitle="{{ __('Engage in public chat or choose somebody for private one.') }}" separator />

    <div class="h-130  overflow-y-scroll">
        @foreach($chatMessages as $message)

            @php
                if ($message->user->avatar) {
                    $avatar = Storage::url('avatars/' . $message->user->avatar);
                }
                else
                {
                    $avatar = null;
                }
            @endphp

            <x-list-item :item="$message->user" link="{{ route('chat', ['user' => $message->user]) }}" >

                <x-slot:avatar>
                    <div class="chat-image avatar {{ empty($avatar) ? 'avatar-placeholder' : '' }} ">
                        <div @class(["w-10", "rounded-full", "bg-neutral text-neutral-content" => empty($avatar) ])>
                            @if(empty($avatar) ) 
                                <span class="text-xs" alt="alt">{{ $message->user->initials() }}</span> 
                            @else
                                <img src="{{ $avatar }}" alt="alt"/> 
                            @endif
                        </div>
                    </div>
                </x-slot:avatar>

                <x-slot:value class="text-wrap">
                    <div>{{ __($message->message) }}</div>
                </x-slot:value>

                <x-slot:sub-value>
                    <div>{{ __($message->user->name) }}</div>

                    <time class="text-xs">{{ $message->created_at->setTimezone( $timezone )->diffForHumans() }}</time>
                </x-slot:sub-value>

            </x-list-item>

        @endforeach
    </div>

    <x-form wire:submit="save" no-separator>
        <x-input label="{{ __('Send a message') }}" wire:model.live="newMessage" placeholder="{{ __('Message') }}" icon="o-chat-bubble-left-right" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Send') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
