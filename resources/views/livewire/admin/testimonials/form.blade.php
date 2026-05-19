<div>
    <x-input wire:model="name" label="Name" />

    <x-input wire:model="role" label="Role" />

    <x-textarea wire:model="content" label="Review" />

    <x-toggle wire:model="is_featured" label="Featured" />

    <x-toggle wire:model="is_active" label="Active" />

    <x-rating wire:model="rating" />
</div>
