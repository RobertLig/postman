<div>
    <x-header subtitle="{{ __('You can match the courier or sender based on their age.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Age') }}
        </x-slot:title>
    </x-header>

    <x-form wire:submit="updateAge" no-separator>

        <x-radio label="{{ __('Age') }}" wire:model="age" :options="$ageOptions" />

        <div class="mt-3 flex gap-2">
            <x-button label="{{ __('Clear selection') }}" icon="o-x-mark" wire:click="clearAge" class="btn-ghost"
                type="button" />

            <x-button label="{{ __('Reset') }}" icon="o-arrow-uturn-left" wire:click="resetAge" class="btn-ghost"
                type="button" />
        </div>

        <x-slot:actions>
            <x-button label="{{ __('Update age') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="updateAge" />
        </x-slot:actions>

    </x-form>
</div>
