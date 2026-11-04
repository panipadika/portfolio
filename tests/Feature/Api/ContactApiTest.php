<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('POST /api/contact successfully saves valid message and returns 201', function () {
    $payload = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'message' => 'Halo! Saya tertarik untuk bekerja sama dalam proyek web.',
    ];

    $response = $this->postJson('/api/contact', $payload);

    $response->assertStatus(201)
        ->assertJson([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi saya.',
        ]);

    $this->assertDatabaseHas('messages', [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'message' => 'Halo! Saya tertarik untuk bekerja sama dalam proyek web.',
        'is_read' => false,
    ]);
});

test('POST /api/contact strips HTML tags from input fields', function () {
    $payload = [
        'name' => '<script>alert("xss")</script>Jane Doe',
        'email' => 'jane@example.com',
        'message' => '<b>Pesan penting</b> dengan tag html yang perlu dibersihkan.',
    ];

    $response = $this->postJson('/api/contact', $payload);

    $response->assertStatus(201);

    $this->assertDatabaseHas('messages', [
        'name' => 'alert("xss")Jane Doe',
        'email' => 'jane@example.com',
        'message' => 'Pesan penting dengan tag html yang perlu dibersihkan.',
    ]);
});

test('POST /api/contact returns 422 when required fields are missing', function () {
    $response = $this->postJson('/api/contact', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'message']);
});

test('POST /api/contact returns 422 when email format is invalid', function () {
    $payload = [
        'name' => 'Jane Doe',
        'email' => 'not-an-email',
        'message' => 'Ini adalah pesan percobaan yang valid dan panjangnya mencukupi.',
    ];

    $response = $this->postJson('/api/contact', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('POST /api/contact returns 422 when message is under 10 characters', function () {
    $payload = [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'message' => 'Pendek',
    ];

    $response = $this->postJson('/api/contact', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['message']);
});

test('POST /api/contact rate limits requests beyond 5 submissions per minute', function () {
    $payload = [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'message' => 'Pesan percobaan valid untuk menguji rate limiting throttle.',
    ];

    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/contact', $payload)->assertStatus(201);
    }

    // 6th request should be throttled
    $response = $this->postJson('/api/contact', $payload);
    $response->assertStatus(429);
});
