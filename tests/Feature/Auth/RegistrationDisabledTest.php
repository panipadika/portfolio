<?php

declare(strict_types=1);

test('registration endpoint returns 404 when registration is disabled', function () {
    if (env('FORTIFY_REGISTRATION', false)) {
        $this->markTestSkipped('Registration is currently enabled.');
    }

    $response = $this->get('/register');
    $response->assertNotFound();

    $postResponse = $this->post('/register', [
        'name' => 'Stranger',
        'email' => 'stranger@example.com',
        'password' => 'password123',
    ]);
    $postResponse->assertNotFound();
});
