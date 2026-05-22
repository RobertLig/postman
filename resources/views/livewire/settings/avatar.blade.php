<div>

    <x-header subtitle="{{ __('By showing your face, you inspire confidence.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Photo') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updatePhoto" no-separator>
        <div>
            <div class="space-y-4">

                <x-file label="{{ __('Photo') }}" wire:model="photo" accept="image/png, image/jpeg, image/webp"
                    hint="{{ __('Max size: 1 MB') }}" />

                <div class="flex justify-center">

                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="h-40 w-40 rounded-xl object-cover shadow-sm" />
                    @elseif ($avatar)
                        <img src="{{ $avatar }}" class="h-40 w-40 rounded-xl object-cover shadow-sm" />
                    @else
                        <div class="flex h-40 w-40 items-center justify-center rounded-xl bg-base-300">

                            <x-user-placeholder class="h-20 w-20 text-base-content/40" />

                        </div>
                    @endif

                </div>

                <div wire:loading wire:target="photo">
                    <x-loading class="loading-spinner loading-md" />
                </div>

            </div>

            @if ($avatar || $photo)
                <x-button wire:click="deletePhoto" icon="o-trash" class="btn-circle btn-ghost"
                    tooltip-right="{{ __('Delete photo') }}" />
            @endif

            <x-hr target="deletePhoto" />
        </div>

        <x-slot:actions>
            <x-button label="{{ __('Update photo') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="updatePhoto" wire:loading.attr="disabled" />
        </x-slot:actions>

    </x-form>

</div>
