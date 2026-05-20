<?php

/* namespace App\Livewire\Admin\Testimonials;

use Livewire\Component;

class ManageTestimonials extends Component
{
    public function render()
    {
        return <<<'HTML'
        <div>
            MANAGE TESTIMONIALS WORKS
        </div>
        HTML;
    }
} */

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;

use Livewire\Component;
use Livewire\Attributes\Validate;

class ManageTestimonials extends Component
{
    public ?int $testimonialId = null;

    protected ?Testimonial $testimonial = null;


    public string $name = '';


    public ?string $role = '';


    public string $content = '';


    public int $rating = 5;


    public bool $is_featured = true;


    public bool $is_active = true;

    public function mount($testimonialId = null): void
    {
        if ($testimonialId?->exists) {

            $this->testimonialId = $testimonialId;

            $this->testimonial = Testimonial::findOrFail($testimonialId);

            $this->fill([
                'name' => $this->testimonial->name,
                'role' => $this->testimonial->role,
                'content' => $this->testimonial->content,
                'rating' => $this->testimonial->rating,
                'is_featured' => $this->testimonial->is_featured,
                'is_active' => $this->testimonial->is_active,
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
                'id' => $this->testimonialId,
            ],
            [
                ...$validated,
                'is_featured' => $this->is_featured,
                'is_active' => $this->is_active,
                'published_at' => $this->testimonialId
                    ? $this->testimonial->published_at
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
