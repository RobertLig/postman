<div
    id="image-list"
    wire:id="{{ $this->getId() }}"
    x-data="{
        images: @entangle('library'),
        remove(i) { $wire.call('removeImage', i); },
    }"
    class="flex flex-col gap-4"
>
    @foreach($library as $i => $img)
        <div
            class="flex items-center gap-2 bg-base-100 rounded-lg p-2"
            draggable="true"
        >
            <img src="{{ $img['url'] }}" class="w-24 h-24 object-cover rounded-lg" />
            <button type="button" wire:click="removeImage({{ $i }})" class="btn btn-error btn-sm ml-2">Delete</button>
        </div>
    @endforeach

    @if(count($library) < 4)
    <div>
        <label class="btn cursor-pointer">
            {{ __('Add Images') }}
            <input type="file" multiple wire:model="files" accept="image/*" class="hidden" />
        </label>
    </div>
    @endif
    @error('files.*') <span class="text-error">{{ $message }}</span> @enderror
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.1/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageList = document.getElementById('image-list');
    const componentId = imageList.getAttribute('wire:id');
    new Sortable(imageList, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function (evt) {
            if (evt.oldIndex !== evt.newIndex) {
                //console.log('Dispatching moveImage', evt.oldIndex, evt.newIndex);
                // Call your Livewire method to update order
                window.Livewire.find(componentId).call('moveImage', { oldIndex: evt.oldIndex, newIndex: evt.newIndex });
                //Livewire.dispatch('moveImageSortable', { oldIndex: evt.oldIndex, newIndex: evt.newIndex });
            }
        }
    });
});
</script>
