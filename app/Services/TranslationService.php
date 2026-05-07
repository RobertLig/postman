<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected string $endpoint = 'http://127.0.0.1:5000/translate';

    public function translate(string $text, string $target, string $source = 'auto'): string
    {
        if (!$text) {
            return $text;
        }

        try {
            $response = Http::timeout(10)->post($this->endpoint, [
                'q' => $text,
                'source' => $source,
                'target' => $target,
                'format' => 'text',
            ]);

            return $response->json('translatedText') ?? $text;
        } catch (\Throwable $e) {
            Log::error('LibreTranslate error', [
                'message' => $e->getMessage(),
                'text' => $text,
            ]);

            return $text; // fallback = original text
        }
    }

    public function translateMany(array $texts, string $target, string $source = 'auto'): array
    {
        return array_map(
            fn($t) => $this->translate($t, $target, $source),
            $texts
        );
    }
}
