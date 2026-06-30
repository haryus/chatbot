<?php

namespace Database\Factories;

use App\Models\FAQ;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FAQ>
 */
class FAQFactory extends Factory
{
    protected $model = FAQ::class;

    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence(6),
            'answer' => $this->faker->paragraph(),
        ];
    }
}
