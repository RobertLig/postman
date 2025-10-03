        <div>
            <x-header title="{{ __('Send a message to ') }} {{ $selectedUser->name }}" subtitle="{{ __('Talk as much as your heart desires.') }}" separator >
                    
                    <x-slot:actions>
                        <x-avatar :image="$avatar" 
                            placeholder="{{ $selectedUser->initials() }}" class="!w-10" />
                    </x-slot:actions>

            </x-header>

            <div class="mt-10 max-w-xl">
                
                @foreach($chatMessages as $message)
                <div class="chat {{ $message->sender_id === auth()->user()->id ? 'chat-end' : 'chat-start'}} ">

                    <div class="chat-image avatar @if(empty($avatar)) avatar-placeholder @endif">
                        <div @class(["w-10", "rounded-full", "bg-neutral text-neutral-content" => empty($avatar)])>
                            @if(empty($avatar))
                                <span class="text-xs" alt="alt">{{ $selectedUser->initials() }}</span>
                            @else
                                <img src="{{ $avatar }}" alt="alt"/>
                            @endif
                        </div>
                    </div> 

                    <div class="chat-header">
                        Obi-Wan Kenobi
                        <time class="text-xs opacity-50">12:45</time>
                    </div>
                    <div class="chat-bubble ">{{ $message->message }}</div>
                    <div class="chat-footer opacity-50">Delivered</div>
                </div>
                @endforeach 

                <div class="chat chat-end">
                    <div class="chat-image avatar">
                        <div class="w-10 rounded-full">
                            <img
                                alt="Tailwind CSS chat bubble component"
                                src="https://img.daisyui.com/images/profile/demo/anakeen@192.webp"
                            />
                        </div>
                    </div>
                    <div class="chat-header">
                        Anakin
                        <time class="text-xs opacity-50">12:46</time>
                    </div>
                    <div class="chat-bubble bg-accent text-accent-content">I hate you!</div>
                    <div class="chat-footer opacity-50">Seen at 12:46</div>
                </div> 

                <x-form wire:submit="save" no-separator>
                    <x-input label="{{ __('Send a message') }}" wire:model.live="newMessage" placeholder="{{ __('Message') }}" icon="o-chat-bubble-left-right" clearable />

                    <x-slot:actions>
                        <x-button label="{{ __('Send') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
                    </x-slot:actions>
                </x-form>
            </div>
        </div>
