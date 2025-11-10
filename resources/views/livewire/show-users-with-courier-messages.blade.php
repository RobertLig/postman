<div>
    @foreach ($usersToReceiveMessagesToTheirAnnouncements as $user)
        @foreach ($user->couriers as $courier)
            <x-list-item :item="$user" value=""
                link="{{ route('chat', ['user' => $user, 'courier' => $courier]) }}"> 

                <x-slot:avatar>
                    <div class="flex -space-x-6">

                        <x-avatar :image="null" alt="alt"
                            placeholder="{{ $courier->initials() }}"
                            class="!w-10 ring-3 ring-base-100 !bg-secondary !text-secondary-content" />

                        <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}"
                            class="!w-10 ring-3 ring-base-100" :badge="$user->countCourierMessages($courier->id)"/>
                    </div>
                </x-slot:avatar>
            </x-list-item>
        @endforeach
    @endforeach

    {{ $usersToReceiveMessagesToTheirAnnouncements->onEachSide(0)->links('vendor.livewire.postman-pagination-messages', ['scrollTo' => false]) }}
</div>
