<?php

namespace Tests\Feature;

use App\Models\FAQ;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FAQAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_faqs(): void
    {
        FAQ::factory()?->create([
            'question' => 'What is your return policy?',
            'answer' => 'You can return items within 30 days.',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/faqs');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'data' => [[
                    'id',
                    'question',
                    'answer',
                ]],
            ]);
    }
}
