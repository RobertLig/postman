<?php

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;

use Livewire\Component;
use Livewire\Attributes\Validate;

class Form extends Component
{
    public ?Testimonial $testimonial = null;

    #[Validate]
    public string $name = '';

    #[Validate]
    public string $role = '';

    #[Validate]
    public string $content = '';

    #[Validate]
    public int $rating = 5;

    #[Validate]
    public bool $is_featured = true;

    #[Validate]
    public bool $is_active = true;

    public function mount(?Testimonial $testimonial = null): void
    {
        $this->testimonial = $testimonial;

        if ($testimonial) {
            $this->fill(
                $testimonial->only([
                    'name',
                    'role',
                    'content',
                    'rating',
                    'is_featured',
                    'is_active',
                ])
            );
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        Testimonial::updateOrCreate(
            [
                'id' => $this->testimonial?->id,
            ],
            [
                ...$validated,
                'is_featured' => $this->is_featured,
                'is_active' => $this->is_active,
                'published_at' => now(),
            ]
        );

        session()->flash(
            'success',
            __('Testimonial saved.')
        );

        $this->redirectRoute(
            'admin.testimonials.index',
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.admin.testimonials.form');
    }
}
