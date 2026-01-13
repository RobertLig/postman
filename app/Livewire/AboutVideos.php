<?php

namespace App\Livewire;

use Livewire\Component;

class AboutVideos extends Component
{
    public function render()
    {
        $locale = app()->getLocale(); // 'en' or 'pl'

        $videos = [
            'en' => ['Map7LAI9RPY', '2EeXzfyXKyA'],
            'pl' => ['IE0DAasF6-U', 'FlXzE782-so'],
        ];

        return view('livewire.about-videos', [
            'videos' => $videos[$locale] ?? $videos['en'], // fallback to EN
        ] );
    }
}
