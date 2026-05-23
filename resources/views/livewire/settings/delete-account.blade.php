<div>
    <x-header subtitle="{{ __('Delete your account and all of its resources.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Delete account') }}
        </x-slot>

        <x-slot:actions>
            <x-button wire:click="deleteAccount" wire:confirm="{{ __('Are you sure?') }}"
                label="{{ __('Delete account') }}" class="btn-error" spinner="deleteAccount" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-header>
</div>
