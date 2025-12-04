<div
    x-data="{
        images: @entangle('library'),
        dragIndex: null,
        dragStart(i) { this.dragIndex = i },
        dragEnter(i) {
            if(this.dragIndex !== null && this.dragIndex !== i) {
                $wire.call('moveImage', this.dragIndex, i);
                this.dragIndex = i;
            }
        },
        remove(i) { $wire.call('removeImage', i); },
    }"
    class="flex flex-col gap-4"
>
    <template x-for="(img, i) in images" :key="i">
        <div
            class="flex items-center gap-2 bg-base-100 rounded-lg p-2"
            draggable="true"
            @dragstart="dragStart(i)"
            @dragover.prevent
            @drop="dragEnter(i)"
        >
            <img :src="img.url" class="w-24 h-24 object-cover rounded-lg" />
            <button type="button" @click="remove(i)" class="btn btn-error btn-sm ml-2">Delete</button>
        </div>
    </template>

    <div x-show="images.length < 4">
        <label class="btn cursor-pointer">
            {{ __('Add Images') }}
            <input type="file" multiple wire:model="files" accept="image/*" class="hidden" />
        </label>
    </div>
    @error('files.*') <span class="text-error">{{ $message }}</span> @enderror
</div>
