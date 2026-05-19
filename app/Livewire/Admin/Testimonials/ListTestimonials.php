<?php

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;

use Livewire\Component;

class ListTestimonials extends Component
{
    public array $headers = [];

    public function mount(): void
    {
        $this->headers = [
            [
                'key' => 'name',
                'label' => __('Name'),
            ],
            [
                'key' => 'rating',
                'label' => __('Rating'),
            ],
            [
                'key' => 'is_featured',
                'label' => __('Featured'),
            ],
            [
                'key' => 'created_at',
                'label' => __('Created'),
            ],
        ];
    }

    public function delete(int $id): void
    {
        Testimonial::findOrFail($id)->delete();

        session()->flash(
            'success',
            __('Testimonial deleted.')
        );
    }

    public function render()
    {
        return view('livewire.admin.testimonials.list-testimonials', [
            'testimonials' => Testimonial::latest()->paginate(10),
        ]);
    }
}
