<?php

namespace Tests\Feature;

use Tests\TestCase;

class StageNineLayoutAuditTest extends TestCase
{
    public function test_public_listing_and_search_pages_have_primary_headings(): void
    {
        foreach (['/jobs' => 'Find your next job', '/latest' => 'Latest Updates', '/search' => 'Search news and resources'] as $url => $heading) {
            $this->get($url)->assertOk()->assertSee('<h1', false)->assertSee($heading);
        }
    }

    public function test_about_and_partnership_pages_link_to_current_contact_form(): void
    {
        $this->get('/about')->assertOk()->assertSee('contact form');
        $this->get('/advertise')->assertOk()->assertSee('select Partnership');
    }

    public function test_contact_and_privacy_pages_are_available(): void
    {
        $this->get('/contact')->assertOk()->assertSee('Send message');
        $this->get('/privacy')->assertOk()->assertSee('Publication review required');
    }
}
