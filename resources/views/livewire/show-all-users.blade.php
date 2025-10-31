<div>
    @foreach ($allUsers as $user)
        <x-list-item :item="$user" value=""
            link="{{ route('chat', ['user' => $user]) }}">

            <x-slot:avatar>
                
                <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}"
                    class="!w-10"
                    :badge="$user->messages_count" />
            
            </x-slot:avatar>
        </x-list-item>
    @endforeach

    {{ $allUsers->onEachSide(0)->links('vendor.livewire.postman-pagination-messages', ['scrollTo' => false]) }}
</div>
