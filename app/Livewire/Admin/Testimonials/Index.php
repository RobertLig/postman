<?php

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.testimonials.index', [
            'testimonials' => Testimonial::latest()->paginate(10),
        ]);
    }
}
