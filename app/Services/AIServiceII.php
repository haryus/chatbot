<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function ask($message, $context = "")
    {
        $response = Http::withHeaders([
            // 'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [

            'model' => env('OPENROUTER_MODEL'),

            'messages' => [
                [
                    'role' => 'system',
                    'content' => $context,
                ],
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ],

        ]);

    // dd($response->status(), $response->json());
        return $response->json()['choices'][0]['message']['content'] ?? 'No response';
    }
}
