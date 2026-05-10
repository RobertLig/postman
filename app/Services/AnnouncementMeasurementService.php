<?php

namespace App\Services;

class AnnouncementMeasurementService
{
    /*
    |--------------------------------------------------------------------------
    | Weights
    |--------------------------------------------------------------------------
    */

    public function syncSenderWeights(
        $announcement,
        ?int $weight,
        string $unit
    ): void {
        if ($weight) {

            if ($unit === 'metric') {
                $metricWeight = $weight;
                $imperialWeight = ceil($weight / 0.45359237);
            } else {
                $metricWeight = ceil($weight * 0.45359237);
                $imperialWeight = $weight;
            }
        } else {
            $metricWeight = null;
            $imperialWeight = null;
        }

        $announcement->weights()->updateOrCreate(
            [
                'metric_or_imperial' => 'imperial',
            ],
            [
                'weight' => $imperialWeight,
            ]
        );

        $announcement->weights()->updateOrCreate(
            [
                'metric_or_imperial' => 'metric',
            ],
            [
                'weight' => $metricWeight,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Dimensions
    |--------------------------------------------------------------------------
    */

    public function syncSenderDimensions(
        $announcement,
        ?int $length,
        ?int $width,
        ?int $height,
        string $unit
    ): void {

        [$metricLength, $imperialLength] =
            $this->convertDimension($length, $unit);

        [$metricWidth, $imperialWidth] =
            $this->convertDimension($width, $unit);

        [$metricHeight, $imperialHeight] =
            $this->convertDimension($height, $unit);

        $announcement->dimensions()->updateOrCreate(
            [
                'metric_or_imperial' => 'imperial',
            ],
            [
                'length' => $imperialLength,

                'width' => $imperialWidth,

                'height' => $imperialHeight,
            ],
        );

        $announcement->dimensions()->updateOrCreate(
            [
                'metric_or_imperial' => 'metric',
            ],
            [
                'length' => $metricLength,

                'width' => $metricWidth,

                'height' => $metricHeight,
            ],
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function convertDimension(
        ?int $value,
        string $unit
    ): array {

        if (!$value) {
            return [null, null];
        }

        if ($unit === 'metric') {

            return [
                $value,
                ceil($value / 2.54),
            ];
        }

        return [
            ceil($value * 2.54),
            $value,
        ];
    }
}
