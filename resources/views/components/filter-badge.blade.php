@props(['clear', 'label'])

<div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
    {{ $label }}

    <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('{{ $clear }}', '')" />
</div>
