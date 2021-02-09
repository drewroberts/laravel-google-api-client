<?php

declare(strict_types=1);

namespace Tipoff\Reviews\Tests\Feature\Nova;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Reviews\Models\Insight;
use Tipoff\Reviews\Tests\Support\Models\User;
use Tipoff\Reviews\Tests\TestCase;

class InsightResourceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function index()
    {
        Insight::factory()->count(4)->create();

        $this->actingAs(User::factory()->create());

        $response = $this->getJson('nova-api/insights')
            ->assertOk();

        $this->assertCount(4, $response->json('resources'));
    }
}
