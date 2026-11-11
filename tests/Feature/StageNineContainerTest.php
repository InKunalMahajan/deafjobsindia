<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageNineContainerTest extends TestCase
{
    use RefreshDatabase;

    public function test_informational_pages_use_shared_container_and_reading_width(): void
    {
        foreach (['/about', '/contact', '/privacy', '/disclaimer', '/advertise'] as $path) {
            $this->get($path)->assertOk()
                ->assertSee('class="dji-container', false)
                ->assertSee('class="dji-content"', false);
        }
    }

    public function test_listing_pages_use_shared_container(): void
    {
        foreach (['/', '/jobs', '/latest', '/employers', '/search'] as $path) {
            $this->get($path)->assertOk()->assertSee('dji-container');
        }
    }
}
