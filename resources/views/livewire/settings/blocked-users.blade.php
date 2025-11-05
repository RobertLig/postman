<div>
    @if ($blockedUsers)
        <x-header subtitle="{{ __('Unblock blocked users.') }}" separator >
            <x-slot:title class="!text-xl">
                {{ __('Blocked users') }}
            </x-slot>
        </x-header>

        @foreach($blockedUsers as $user)
            <x-list-item :item="$user" >
                <x-slot:avatar>
                    <x-avatar :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}"
                        class="!w-10" />
                </x-slot:avatar>

                
                <x-slot:actions>
                    <x-button icon="o-user-plus" class="btn-sm" :tooltip="__('Unblock')" wire:click="unblockUser({{ $user->id }})" spinner />
                </x-slot:actions>
                 
            </x-list-item>
        @endforeach
    @endif
</div>
