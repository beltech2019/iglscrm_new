<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SentimentHelper
{
    public static function getSentiment($text)
    {
        if (empty($text)) {
            return 'Neutral';
        }

        return self::analyze($text);
    }

    private static function analyze($text)
    {
        $response = Http::withToken(env('HF_API_KEY'))
            ->timeout(30)
            ->post(
                'https://router.huggingface.co/hf-inference/models/cardiffnlp/twitter-roberta-base-sentiment-latest',
                [
                    'inputs' => $text
                ]
            );

        $result = $response->json();

        if (
            !$response->successful() ||
            !isset($result[0][0])
        ) {
            return 'Neutral';
        }

        $predictions = $result[0];

        usort($predictions, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $label = strtolower($predictions[0]['label'] ?? '');

        if ($label === 'negative') {
            return 'Negative';
        }

        if ($label === 'positive') {
            return 'Positive';
        }

        return 'Neutral';
    }
}