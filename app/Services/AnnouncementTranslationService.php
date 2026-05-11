<?php

namespace App\Services;

use App\Models\Language;
use App\Models\MonthTranslation;
use Illuminate\Support\Facades\App;

class AnnouncementTranslationService
{
    public function __construct(
        private TranslationService $translator
    ) {}

    public function syncTranslations(
        $announcement,
        array $data
    ) {

        $english = Language::where('code', 'en')->first();
        $polish = Language::where('code', 'pl')->first();

        /*
        |--------------------------------------------------------------------------
        | Months
        |--------------------------------------------------------------------------
        */

        $postingMonth = MonthTranslation::where(
            'month',
            $data['posting_month']
        )->first();

        $receptionMonth = MonthTranslation::where(
            'month',
            $data['reception_month']
        )->first();

        $englishPostingMonth = MonthTranslation::where(
            'month_id',
            $postingMonth->month_id
        )->where('language_id', $english->id)->first();

        $englishReceptionMonth = MonthTranslation::where(
            'month_id',
            $receptionMonth->month_id
        )->where('language_id', $english->id)->first();

        $polishPostingMonth = MonthTranslation::where(
            'month_id',
            $postingMonth->month_id
        )->where('language_id', $polish->id)->first();

        $polishReceptionMonth = MonthTranslation::where(
            'month_id',
            $receptionMonth->month_id
        )->where('language_id', $polish->id)->first();

        /*
        |--------------------------------------------------------------------------
        | English
        |--------------------------------------------------------------------------
        */

        $currentLocale = App::currentLocale();

        $announcement->translations()->updateOrCreate(
            [
                'lang_id' => $english->id,
            ],
            [
                'thing' => $this->translator->translate(
                    $data['thing'],
                    'en',
                    $currentLocale
                ),

                'description' => $data['description']
                    ? $this->translator->translate(
                        $data['description'],
                        'en',
                        $currentLocale
                    )
                    : null,

                'posting_place' => $this->translator->translate(
                    $data['posting_place'],
                    'en',
                    $currentLocale
                ),

                'reception_place' => $this->translator->translate(
                    $data['reception_place'],
                    'en',
                    $currentLocale
                ),

                'posting_month' => $englishPostingMonth->month,

                'reception_month' => $englishReceptionMonth->month,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Polish
        |--------------------------------------------------------------------------
        */

        $announcement->translations()->updateOrCreate(
            [
                'lang_id' => $polish->id,
            ],
            [
                'thing' => $this->translator->translate(
                    $data['thing'],
                    'pl',
                    $currentLocale
                ),

                'description' => $data['description']
                    ? $this->translator->translate(
                        $data['description'],
                        'pl',
                        $currentLocale
                    )
                    : null,

                'posting_place' => $this->translator->translate(
                    $data['posting_place'],
                    'pl',
                    $currentLocale
                ),

                'reception_place' => $this->translator->translate(
                    $data['reception_place'],
                    'pl',
                    $currentLocale
                ),

                'posting_month' => $polishPostingMonth->month,

                'reception_month' => $polishReceptionMonth->month,
            ]
        );

        /* dd(
            $announcement->translations()->get()->toArray()
        ); */
    }
}
