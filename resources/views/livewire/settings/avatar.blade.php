<div>

    <x-header subtitle="{{ __('By showing your face, you inspire confidence.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Photo') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updatePhoto" no-separator>
        <div>
            <div wire:key="upload-{{ $iteration }}">

                <x-file label="{{ __('Photo') }}" wire:model="photo" accept="image/*" {{-- "image/png, image/jpeg" --}}
                    change-text="{{ __('Change') }}">

                    <img wire:key="{{ $avatar }}-{{ $photo?->getFilename() }}"
                        src="{{ $photo ? $photo->temporaryUrl() : $avatar ?? Storage::disk('public')->url('avatars/empty-user.jpg') }}"
                        class="h-40 rounded-lg" />

                </x-file>
            </div>

            @if ($avatar)
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
