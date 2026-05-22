<div>
    <x-header subtitle="{{ __('You can match the courier or sender based on their age.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Age') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updateAge" no-separator>

        <x-age />

        <x-slot:actions>
            <x-button label="{{ __('Update age') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="updateAge" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-form>
</div>
