<div>
    <x-header subtitle="{{ __('Couriers and senders can be selected based on gender.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Gender') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updateGender" no-separator>

        <x-gender />

        <x-slot:actions>
            <x-button label="{{ __('Update gender') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="updateGender" wire:loading.attr="disabled" />
        </x-slot:actions>

    </x-form>

</div>
