<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

new #[Title('Messages')]
class extends Component {
    #[Validate('nullable|string|max:200')]
    public $newMessage;

    public $chatMessages;

    public function mount() 
    {
        $this->setMessages();


    }

    public function setMessages()
    {
        $this->chatMessages = Message::query()
            ->where(function(Builder $query) {
                $query->where('recipient_id', ''); 
            })
            ->get(); //show all messages without recipient
    }

    public function save()
    {
        $this->validate();

        if(!$this->newMessage) {return;}

        $message = Message::create([
            'sender_id' => Auth::user()->id,
            'message' => $this->newMessage,
        ]); 

        $this->chatMessages->push($message);

        $this->newMessage = null; 

        //broadcast(new MessageSent($message)); //uncomment later for public chanel
    }
}; ?>

<div>
    <x-header title="{{ __('Messages') }}" subtitle="{{ __('Engage in public chat or choose somebody for private one.') }}" separator />

    <div class="h-130 bg-amber-500">
        
    </div>

    <x-form wire:submit="save" no-separator>
        <x-input label="{{ __('Send a message') }}" wire:model.live="newMessage" placeholder="{{ __('Message') }}" icon="o-chat-bubble-left-right" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Send') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
