<?php

namespace App\View\Components;

use Closure;
use App\Models\Testimonial;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Reviews extends Component
{
    public function __construct() {}

    public function testimonials()
    {
        return Testimonial::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit(6)
            ->get();
    }

    public function render(): View|Closure|string
    {
        return view('components.reviews', [
            'testimonials' => $this->testimonials(),
        ]);
    }
}
