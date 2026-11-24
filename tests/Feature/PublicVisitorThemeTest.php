<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicVisitorThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_navigation_hides_login_button_and_shows_theme_toggle(): void
    {
        $response = $this->get(route('public.home'));

        $response->assertOk()
            ->assertSee('id="publicThemeToggle"', false)
            ->assertSee('visitor-theme', false)
            ->assertDontSee('href="'.route('login').'"', false);
    }
}
