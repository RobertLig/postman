<div>
    <ul id="image-list" x-data x-init="Sortable.create($el, {
        animation: 150,
        onEnd: function(evt) {
            $wire.moveImage({ oldIndex: evt.oldIndex, newIndex: evt.newIndex });
        }
    })">
        @foreach ($allImages as $i => $img)
            <li class="flex items-center gap-2 bg-base-100 rounded-lg p-2" data-id="{{ $i }}">
                <img src="{{ $img['url'] }}" class="w-24 h-24 object-cover rounded-lg" />

                <x-button label="{{ __('Delete') }}" class="btn-warning" type="button"
                    wire:click="removeImage({{ $i }})" />

            </li>
        @endforeach
    </ul>

    @if (count($allImages) < 3)
        <div>
            <label class="btn">
                {{ __('Add Images') }}
                <input type="file" multiple wire:model="files" accept="image/*" class="hidden" />
            </label>
            <p class="mt-2 text-xs" style="color: var(--p);">
                {{ __('Tip: To add multiple images, select them all at once in the file picker.') }}
            </p>
        </div>
    @endif
    @error('files.*')
        <span class="text-error">{{ $message }}</span>
    @enderror
    <x-hr target="files" />
</div>
