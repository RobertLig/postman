<?php

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;

use Livewire\Component;
use Livewire\Attributes\Validate;

class ManageTestimonials extends Component
{
    public ?int $testimonial = null;

    public ?Testimonial $testimonialModel = null;


    public string $name = '';


    public ?string $role = '';


    public string $content = '';


    public int $rating = 5;


    public bool $is_featured = true;


    public bool $is_active = true;

    public function mount($testimonial = null): void
    {
        //dd($testimonial);
        if ($testimonial) {

            $this->testimonial = $testimonial;

            $this->testimonialModel = Testimonial::findOrFail($testimonial);

            $this->fill([
                'name' => $this->testimonialModel->name,
                'role' => $this->testimonialModel->role,
                'content' => $this->testimonialModel->content,
                'rating' => $this->testimonialModel->rating,
                'is_featured' => $this->testimonialModel->is_featured,
                'is_active' => $this->testimonialModel->is_active,
            ]);
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
                'id' => $this->testimonial,
            ],
            [
                ...$validated,
                'is_featured' => $this->is_featured,
                'is_active' => $this->is_active,
                'published_at' => $this->testimonial
                    ? $this->testimonialModel->published_at
                    : now(),
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
        return view('livewire.admin.testimonials.manage-testimonials');
    }
}
