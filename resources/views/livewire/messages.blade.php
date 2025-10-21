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
use Illuminate\Support\Facades\Log;

new #[Title('Messages')]
    class extends Component {
    #[Validate('nullable|string|max:500')]
    public $newMessage;

    public $chatMessages;

    public $timezone;

    public $presenceIndicator;

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
            "echo:chat,PublicMessageSent" => 'newPublicMessageNotification',
            "echo-presence:publicChatroom,here" => 'publicHere',
            "echo-presence:publicChatroom,joining" => 'publicJoining',
            "echo-presence:publicChatroom,leaving" => 'publicLeaving'
        ];
    }

    public function newPublicMessageNotification($message)
    {
        $messageModel = Message::find($message['id']);

        $this->chatMessages->push($messageModel);

        $this->dispatch('messages-updated'); //only works on recipients' side
    }

    //#[On('echo-presence:chatroom,here')]
    public function publicHere($users) //for event dispatcher
    {
        /* foreach($users as $user)
        {
            $user = User::find($user['id']);

            $this->presentUsers->push($user);
        } */

        //dd($users);
        //if(isset($users['senderAnnouncementID']) && $users['senderAnnouncementID'] == $this->senderAnnouncementID) //never executes
        //{
            Log::info('All users: {users}', ['users' => $users]);

            if(count($users) == 2) //don't show me a user if he is not in the chatroom
            {
                $this->presenceIndicator = 1; //1
            } 
        //}
    }

    //#[On('echo-presence:chatroom,joining')]
    public function publicJoining($user) //for event recipient
    {
        //dd($user);
        //if(isset($user['senderAnnouncementID']) && $user['senderAnnouncementID'] == $this->senderAnnouncementID) //not needed
        //{
            Log::info('Joining: {user}', ['user' => $user]);

            $this->presenceIndicator = 1; //1
        //}

        /* $user = User::find($user['id']);

        $this->presentUsers->push($user); */
    }

    //#[On('echo-presence:chatroom,leaving')]
    public function publicLeaving($user) //for event recipient
    {
        //dd($user);

        //if(isset($user['senderAnnouncementID']) && $user['senderAnnouncementID'] == $this->senderAnnouncementID) //not needed
        //{
            Log::info('Leaving: {user}', ['user' => $user]);

            $this->presenceIndicator = 0; //0
        //}

        /* $userModel = User::find($user['id']);

        $this->presentUsers = $this->presentUsers->filter(function ($value, int $key) use ($userModel) {
            return $value->id != $userModel->id;
        }); */
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

        broadcast(new PublicMessageSent($message))->toOthers();

        $this->dispatch('messages-updated'); //only works on sender side
    }
}; ?>

<div x-data="{
    handleMessagesUpdatedEvent($event)
    {
        console.log($refs.chatcontainer); 

        $nextTick(() => { $refs.chatcontainer.scrollTo(0, $refs.chatcontainer.scrollHeight) });
    } 
}" >
    <x-header title="{{ __('Messages') }}" subtitle="{{ __('Engage in public chat or choose somebody for private one.') }}" separator />

    <x-card shadow>
        <div class="h-130  overflow-y-scroll" x-on:messages-updated.window="handleMessagesUpdatedEvent" x-ref="chatcontainer"
            {{-- id="chat-container" test--}}>
            @foreach($chatMessages as $message)
            
                <x-list-item :item="$message->user" link="{{ route('chat', ['user' => $message->user]) }}">

                    <x-slot:avatar>
                        <x-avatar-with-indicator :image="$message->user->getAvatar()" alt="alt" 
                            placeholder="{{ $message->user->initials() }}" class="!w-10" 
                            :presenceIndicator="$message->user->id != auth()->user()->id ? $presenceIndicator : null" />

                        {{-- <x-avatar :image="$message->user->getAvatar()" 
                            placeholder="{{ $message->user->initials() }}" class="!w-10" /> --}}
                    </x-slot:avatar>

                    <x-slot:value class="text-wrap">
                        <div>{{ __($message->message) }}</div>
                    </x-slot:value>

                    <x-slot:sub-value>
                        <div>{{ __($message->user->name) }}</div>

                        <time class="text-xs">{{ $message->created_at->setTimezone($timezone)->diffForHumans() }}</time>
                    </x-slot:sub-value>

                </x-list-item>

            @endforeach
        </div>
    </x-card>

    <x-form wire:submit="save" no-separator>
        <x-input label="{{ __('Send a message') }}" wire:model.live="newMessage" placeholder="{{ __('Message') }}" icon="o-chat-bubble-left-right" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Send') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>

    <livewire:message-box /> 

    {{-- <script type="module"> test
        let chatContainer = document.getElementById("chat-container");

        Livewire.on('messages-updated', (event) => {
            console.log('scrolling');

            chatContainer.scrollTo(0, chatContainer.scrollHeight); 

            //chatContainer.scrollTop = chatContainer.scrollHeight; //works the same way
        });
    </script> --}}
</div>
