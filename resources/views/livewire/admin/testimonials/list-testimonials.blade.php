<div class="space-y-5">

    @if (session('success'))
        <x-alert icon="o-check-circle" class="alert-success mb-5">
            {{ session('success') }}
        </x-alert>
    @endif

    <div class="flex justify-end">

        <x-button label="{{ __('Add testimonial') }}" icon="o-plus" :link="route('admin.testimonials.create')" class="btn-primary" />

    </div>

    <x-table :headers="$headers" :rows="$testimonials" with-pagination>

        @scope('cell_rating', $testimonial)
            <div class="flex gap-0.5">

                @for ($i = 1; $i <= 5; $i++)
                    <x-icon name="{{ $i <= $testimonial->rating ? 's-star' : 'o-star' }}" class="w-4 h-4 text-warning" />
                @endfor

            </div>
        @endscope

        @scope('cell_is_featured', $testimonial)
            <x-badge :value="$testimonial->is_featured ? __('Featured') : __('Hidden')" class="{{ $testimonial->is_featured ? 'badge-success' : 'badge-ghost' }}" />
        @endscope

        @scope('cell_created_at', $testimonial)
            {{ $testimonial->created_at?->format('Y-m-d') }}
        @endscope

        @scope('actions', $testimonial)
            <div class="flex gap-2">

                <x-button icon="o-pencil" :link="route('admin.testimonials.edit', $testimonial)" class="btn-sm btn-ghost" />

                <x-button icon="o-trash" wire:click="delete({{ $testimonial->id }})"
                    wire:confirm="{{ __('Delete testimonial?') }}" class="btn-sm btn-ghost text-error" />

            </div>
        @endscope

    </x-table>

</div>
