<?php

namespace App\Livewire;

use Livewire\Component;

class AboutVideos extends Component
{
    public function render()
    {
        $locale = app()->getLocale(); // 'en' or 'pl'

        $videos = [
            'en' => ['lpItc7R0yOo', 'yTo2mt4H384'],
            'pl' => ['J2PaLq997xU', 'W4yPmlWLWL0'],
        ];

        return view('livewire.about-videos', [
            'videos' => $videos[$locale] ?? $videos['en'], // fallback to EN
        ]);
    }
}
