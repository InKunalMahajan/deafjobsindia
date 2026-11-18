<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicDesignReviewTest extends TestCase {
    use RefreshDatabase;

    public function test_public_directory_pages_have_primary_heading(): void {
        foreach (['/jobs', '/latest', '/search', '/employers'] as $url) {
            $this->get($url)->assertOk()->assertSee('<h1', false);
        }
    }

    public function test_homepage_search_and_mobile_menu_exist(): void {
        $this->get('/')->assertOk()
            ->assertSee('name="q"', false)
            ->assertSee('Mobile navigation')
            ->assertSee('Latest open jobs')
            ->assertSee('Latest updates');
    }

    public function test_contact_page_does_not_promise_unavailable_form(): void {
        $this->get('/contact')->assertOk()->assertSee('Contact form coming soon');
    }
}
