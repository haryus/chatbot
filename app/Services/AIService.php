<?php

namespace App\Services;

use App\Models\AiProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AIService
{
    public function ask($message, $context = "")
    {
        $providerData = Cache::remember('active_ai_provider', 300, function () {
            $p = AiProvider::where('is_active', true)->first();
            return $p ? $p->toArray() : null;
        });

        if (! $providerData) {
            return 'AI provider is not configured. Please contact an administrator.';
        }

        $provider = (object) $providerData;

        return match ($provider->provider) {
            'gemini' => $this->askGemini($provider, $message, $context),

            'openrouter',
            'openai',
            'groq',
            'cerebras',
            'github' => $this->askOpenAiCompatible($provider, $message, $context),

            default => throw new \Exception("Unsupported AI provider: {$provider->provider}"),
        };
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
    private function askOpenAiCompatible($provider, $message, $context)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $provider->api_key,
            'Content-Type'  => 'application/json',
        ];

        // GitHub Models requires an API version header.
        if ($provider->provider === 'github') {
            $headers['X-GitHub-Api-Version'] = '2022-11-28';
        }

        $response = Http::withHeaders($headers)
            ->timeout(60)
            ->post(
                rtrim($provider->base_url, '/') . '/chat/completions',
                [
                    'model' => $provider->model,

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

                    'temperature' => 0.7,
                    'max_tokens' => 512,
                ]
            );

        if (!$response->successful()) {
            throw new \Exception(
                $response->json()['error']['message']
                    ?? $response->body()
            );
        }

        return $response->json()['choices'][0]['message']['content']
            ?? 'No response';
    }
    private function askGemini($provider, $message, $context)
    {
        $endpoint =
            rtrim($provider->base_url, '/')
            . "/models/{$provider->model}:generateContent?key={$provider->api_key}";

        $body = [
            'systemInstruction' => [
                'parts' => [
                    [
                        'text' => $context,
                    ]
                ]
            ],

            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'text' => $message,
                        ]
                    ]
                ]
            ],

            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 512,
            ],
        ];

        $response = Http::post($endpoint, $body);

        if (!$response->successful()) {
            throw new \Exception(
                $response->json()['error']['message']
                    ?? $response->body()
            );
        }
        // dd($response->json());
        return $response->json()['candidates'][0]['content']['parts'][0]['text']
            ?? 'No response';
    }
}
