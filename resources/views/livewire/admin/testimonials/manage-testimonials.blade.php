<div>
    <form wire:submit="save" class="space-y-5">

        <x-input wire:model="name" label="Name" />

        <x-input wire:model="role" label="Role" />

        <x-textarea wire:model="content" label="Review" />

        <x-toggle wire:model="is_featured" label="Featured" />

        <x-toggle wire:model="is_active" label="Active" />

        <x-rating wire:model="rating" />

        <div>
            <x-button type="submit" label="{{ __('Save') }}" class="btn-primary" />
        </div>
    </form>
</div>
