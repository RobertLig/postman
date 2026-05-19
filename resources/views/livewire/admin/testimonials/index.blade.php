<div>
    <x-table :headers="$headers" :rows="$testimonials">
        @scope('cell_avatar', $testimonial)
            <x-avatar :image="$testimonial->avatar" :placeholder="$testimonial->initials()" class="!w-10" />
        @endscope

        @scope('cell_is_featured', $testimonial)
            <x-badge :value="$testimonial->is_featured ? 'Featured' : 'Hidden'" class="{{ $testimonial->is_featured ? 'badge-success' : 'badge-ghost' }}" />
        @endscope
    </x-table>
</div>
