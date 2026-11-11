<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_internal_preview_is_available_in_local_environment(): void
    {
        app()->detectEnvironment(fn () => 'local');
        $this->get('/design-system')
            ->assertOk()
            ->assertSee('Design system')
            ->assertSee('Verified source')
            ->assertSee('Skip to main content');
    }

    public function test_navigation_has_accessible_labels(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Primary navigation')
            ->assertSee('Mobile navigation')
            ->assertSee('Footer navigation');
    }

    public function test_internal_preview_is_not_available_in_production(): void
    {
        app()->detectEnvironment(fn () => 'production');
        $this->get('/design-system')->assertNotFound();
    }
}
