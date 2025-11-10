<div>
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
                    @foreach ($presentUsersTransformedAndPaginated as $presentUser)
                        @if ($presentUser[0] == $courier->id)
                            <x-list-item :item="$presentUser[1]"
                                link="{{ route('chat', ['user' => $presentUser[1], 'courier' => $courier]) }}">
                                {{-- --}}
                                <x-slot:avatar>
                                    <x-avatar :image="$presentUser[1]->getAvatar()" alt="alt"
                                        placeholder="{{ $presentUser[1]->initials() }}" class="!w-10" />
                                </x-slot:avatar>
                            </x-list-item>
                        @endif
                    @endforeach
                </div>

            </div>

            {{-- {{ $courier->id }} --}}

        @endforeach
        
    </div>

    {{ $presentUsersTransformedAndPaginated->onEachSide(0)->links('vendor.livewire.postman-pagination-messages', ['scrollTo' => false]) }}
</div>
