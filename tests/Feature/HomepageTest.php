<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_minimal_site(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Find work.')
            ->assertSee('DeafJobsIndia')
            ->assertSee('Skip to main content');
    }

    public function test_jobs_and_search_pages_render(): void
    {
        $this->get('/jobs')->assertOk();
        $this->get('/search')->assertOk();
        $this->get('/about')->assertOk();
    }
}
