<?php

declare(strict_types=1);

use App\Models\SiteSetting;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('admin can view site settings page', function () {
    $response = $this->get(route('admin.settings.index'));
    $response->assertOk();
    $response->assertViewIs('admin.settings.index');
});

test('admin can update contact and social media settings', function () {
    $response = $this->put(route('admin.settings.update'), [
        'contact_email' => 'admin.baru@example.com',
        'contact_phone' => '+62 899-1234-5678',
        'contact_whatsapp' => 'https://wa.me/6289912345678',
        'contact_location' => 'Bandung, Jawa Barat',
        'bio_headline' => 'Full-Stack Software Engineer & Open Source Enthusiast',
        'social_github' => 'https://github.com/newuser',
        'social_linkedin' => 'https://linkedin.com/in/newuser',
        'social_instagram' => 'https://instagram.com/newuser',
        'social_tiktok' => 'https://tiktok.com/@newuser',
    ]);

    $response->assertRedirect(route('admin.settings.index'));
    $response->assertSessionHas('success');

    expect(SiteSetting::get('contact_email'))->toBe('admin.baru@example.com');
    expect(SiteSetting::get('contact_phone'))->toBe('+62 899-1234-5678');
    expect(SiteSetting::get('social_github'))->toBe('https://github.com/newuser');

    // Verify GET /api/portfolio delivers the updated settings
    $apiResponse = $this->getJson('/api/portfolio');
    $apiResponse->assertOk();
    $apiResponse->assertJsonPath('data.settings.contact_email', 'admin.baru@example.com');
    $apiResponse->assertJsonPath('data.settings.contact_phone', '+62 899-1234-5678');
    $apiResponse->assertJsonPath('data.settings.social_github', 'https://github.com/newuser');
});
