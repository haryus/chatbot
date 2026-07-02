<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use Illuminate\Database\Seeder;

class AiProviderSeeder extends Seeder
{
    public function run(): void
    {
        AiProvider::updateOrCreate(
            ['provider' => 'openrouter'],
            [
                'name'      => 'OpenRouter',
                'api_key'   => env('OPENROUTER_API_KEY', ''),
                'model'     => env('OPENROUTER_MODEL', 'deepseek/deepseek-chat-v3-0324'),
                'base_url'  => null,
                'is_active' => true,
            ]
        );
    }
}
