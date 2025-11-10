<div>
    @if($couriers->isNotEmpty())
    <div class="flex flex-wrap gap-5">
        @foreach ($couriers as $courier)

            <div wire:key="{{ $courier->id }}">
                <x-list-item :item="$courier"
                    link="{{ route('couriers-announcements.show', ['courier' => $courier]) }}">
                    <x-slot:avatar>
                        <x-popover position="top-start">
                            <x-slot:trigger>
                                <x-avatar :image="null" alt="alt"
                                    placeholder="{{ $courier->initials() }}"
                                    class="!w-10 !bg-secondary !text-secondary-content" />
                            </x-slot:trigger>
                            <x-slot:content>
                                {{ $courier->title() }}
                            </x-slot:content>
                        </x-popover>
                    </x-slot:avatar>
                </x-list-item>

                <div>
                    @foreach ($users as $user)
                        @if($user->hasSentMessageToThisCourier($courier->id))
                            <x-list-item :item="$user"
                                link="{{ route('chat', ['user' => $user, 'courier' => $courier]) }}"> {{--
                                --}}
                                <x-slot:avatar>
                                    <x-avatar-with-badge :image="$user->getAvatar()" alt="alt" placeholder="{{ $user->initials() }}"
                                        class="!w-10" :badge="$user->countCourierMessages($courier->id)" />
                                </x-slot:avatar>
                            </x-list-item>
                        @endif
                    @endforeach
                </div>
            </div>

        @endforeach
    </div>

    {{-- dd($users) --}}

    {{ $users->onEachSide(0)->links('vendor.livewire.postman-pagination-messages', ['scrollTo' => false]) }}
    @endif
</div>
