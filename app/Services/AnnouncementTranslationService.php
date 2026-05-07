<?php

namespace App\Services;

use App\Models\Language;
use App\Models\MonthTranslation;

class AnnouncementTranslationService
{
    public function createSenderTranslations(
        $announcement,
        array $data
    ): void {

        $translator = app(TranslationService::class);

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

        $announcement->translations()->create([
            'lang_id' => $english->id,

            'thing' => $translator->translate(
                $data['thing'],
                'en'
            ),

            'description' => $data['description']
                ? $translator->translate(
                    $data['description'],
                    'en'
                )
                : null,

            'posting_place' => $translator->translate(
                $data['posting_place'],
                'en'
            ),

            'reception_place' => $translator->translate(
                $data['reception_place'],
                'en'
            ),

            'posting_month' => $englishPostingMonth->month,

            'reception_month' => $englishReceptionMonth->month,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Polish
        |--------------------------------------------------------------------------
        */

        $announcement->translations()->create([
            'lang_id' => $polish->id,

            'thing' => $translator->translate(
                $data['thing'],
                'pl'
            ),

            'description' => $data['description']
                ? $translator->translate(
                    $data['description'],
                    'pl'
                )
                : null,

            'posting_place' => $translator->translate(
                $data['posting_place'],
                'pl'
            ),

            'reception_place' => $translator->translate(
                $data['reception_place'],
                'pl'
            ),

            'posting_month' => $polishPostingMonth->month,

            'reception_month' => $polishReceptionMonth->month,
        ]);
    }
}
