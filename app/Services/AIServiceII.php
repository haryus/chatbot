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

    // private function askOpenAiCompatible($provider, $message, $context)
    // {

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
    //         'Content-Type' => 'application/json',
    //     ])->post('https://openrouter.ai/api/v1/chat/completions', [

    //         'model' => env('OPENROUTER_MODEL'),

    //         'messages' => [
    //             [
    //                 'role' => 'system',
    //                 'content' => $context,
    //             ],
    //             [
    //                 'role' => 'user',
    //                 'content' => $message,
    //             ],
    //         ],

    //     ]);

    //     dd($response->status(), $response->json());
    //     //     return $response->json()['choices'][0]['message']['content'] ?? 'No response';
    //     // $baseUrl = $provider->base_url
    //     //     ?? match ($provider->provider) {
    //     //         'openai' => 'https://api.openai.com/v1',
    //     //         default  => 'https://openrouter.ai/api/v1',
    //     //     };

    //         //     $response = Http::withHeaders([
    //         //         'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
    //         //         'Content-Type'  => 'application/json',
    //         //     ])->post('https://openrouter.ai/api/v1/chat/completions', [
    //         //         'model'    => 'deepseek/deepseek-r1-0528:free',
    //         //         'messages' => [
    //         //             ['role' => 'system', 'content' => $context],
    //         //             ['role' => 'user',   'content' => $message],
    //         //         ],
    //         // 'max_tokens' => 512,
    //         //     ]);

    //     //     dd([
    //     //     'status' => $response->status(),
    //     //     'headers' => $response->headers(),
    //     //     'body' => $response->body(),
    //     //     'json' => $response->json(),
    //     // ]);
    //     return $response->json()['choices'][0]['message']['content'] ?? 'No response';
    // }
}
