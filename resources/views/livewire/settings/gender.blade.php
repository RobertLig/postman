<div>
    <x-header subtitle="{{ __('Couriers and senders can be selected based on gender.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Gender') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updateGender" no-separator>

        <x-radio label="{{ __('Gender') }}" wire:model="gender" :options="$genderOptions" />

        <div class="mt-3 flex gap-2">
            <x-button label="{{ __('Clear selection') }}" icon="o-x-mark" wire:click="clearGender" class="btn-ghost"
                type="button" />

            <x-button label="{{ __('Reset') }}" icon="o-arrow-uturn-left" wire:click="resetGender" class="btn-ghost"
                type="button" />
        </div>

        <x-slot:actions>
            <x-button label="{{ __('Update gender') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="updateGender" />
        </x-slot:actions>

    </x-form>

    <x-hr />
</div>
